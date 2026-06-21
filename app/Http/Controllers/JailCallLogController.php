<?php

namespace App\Http\Controllers;

use App\Models\JailCallLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JailCallLogController extends Controller
{
    private const BATCH_SIZE = 1000;
    

    public function show()
    {

        $categories = JailCallLog::distinct()
            ->orderBy('termination_category')
            ->pluck('termination_category');


        $calls = JailCallLog::query()
            ->where('termination_category', '3 way call detected')
            ->orderBy('start_time')
            ->paginate(200);

        return view('imports.show', compact('calls', 'categories'));
    }

    public function showForm()
    {
        return view('imports.jail-call-logs');
    }


    public function edit(JailCallLog $jailCallLog)
    {  
        return view('imports.edit', [
            'jailCallLog' => $jailCallLog,
        ]);
    }

    public function update(Request $request)
    {
        $log = JailCallLog::findOrFail($request->id);

        $data = [];

        if ($request->filled('start_time')) {
            $data['start_time'] = $this->toMysqlDateTime($request->start_time);
            $data['start_time_error'] = 'corrected';
        }

        if ($request->filled('end_time')) {
            $data['end_time'] = $this->toMysqlDateTime($request->end_time);
            $data['end_time_error'] = 'corrected';
        }

        if (!empty($data)) {
            $log->update($data);
        }

        return redirect('call-logs')->with('status', [
            'type' => 'success',
            'message' => 'Call log record updated for ID #' . $request->id
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:51200',
        ]);

        $path = $request->file('csv_file')->getRealPath();

        try {
            $count = $this->importCsv($path);
        } catch (\Throwable $e) {
            Log::error('CSV import failed', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'csv_file' => 'Import failed. Check file format.',
            ]);
        }

        return back()->with('success', "Imported rows: {$count}");
    }

    private function importCsv(string $path): int
    {
        $handle = fopen($path, 'r');

        if (!$handle) {
            throw new \RuntimeException('Cannot open CSV file.');
        }

        // Read header just for reference (not used for mapping)
        $headers = fgetcsv($handle, 0, ';', '"');

        $batch = [];
        $count = 0;

        while (($row = fgetcsv($handle, 0, ';', '"')) !== false) {


            // Skip invalid rows safely
            if (!is_array($row) || count($row) < 17) {
                continue;
            }

            $rawStartTime = $row[3] ?? null;
            $parsedStartTime = $this->toMysqlDateTime($rawStartTime);

            $rawEndTime = $row[4] ?? null;
            $parsedEndTime = $this->toMysqlDateTime($rawEndTime);

            $batch[] = [
                // POSITIONS (based on your real dump)
                'site' => trim($row[0] ?? ''),
                'term_group_name' => trim($row[1] ?? ''),
                'term_name' => trim($row[2] ?? ''),

                // MULTILINE DATE FIX
                'start_time' => $parsedStartTime,
                'raw_start_time' => $parsedStartTime ? null : $rawStartTime,
                'start_time_error' => $parsedStartTime ? null : 'parse_failed',

                'end_time' => $parsedEndTime,
                'raw_end_time' => $parsedEndTime ? null : $rawEndTime,
                'end_time_error' => $parsedEndTime ? null : 'parse_failed',
                

                'duration' => (int) ($row[5] ?? 0),

                'service_type' => trim($row[6] ?? ''),
                'comm_type' => trim($row[7] ?? ''),
                'comm_status' => trim($row[8] ?? ''),
                'termination_category' => trim($row[9] ?? ''),

                'first_name' => trim($row[10] ?? ''),
                'last_name' => trim($row[11] ?? ''),

                'account_number' => trim($row[12] ?? ''),
                'pin' => trim($row[13] ?? ''),

                'other_party' => trim($row[14] ?? ''),

                'is_private' => filter_var($row[15] ?? false, FILTER_VALIDATE_BOOLEAN),

                'language' => trim($row[16] ?? ''),

                'created_at' => now(),
                'updated_at' => now(),
            ];

            // dd($batch);

            $count++;

            if (count($batch) >= self::BATCH_SIZE) {
                try {
                    DB::table('jail_call_logs')->insert($batch);
                } catch (\Throwable $e) {
                    dd([
                        'error' => $e->getMessage(),
                        'row' => $batch[0] ?? null,
                    ]);
                }
                $batch = [];
            }
        }

        fclose($handle);

        if (!empty($batch)) {
            DB::table('jail_call_logs')->insert($batch);
        }

        return $count;
    }

    /**
     * Fix multiline jail export datetime like:
     * "04/09/25\n03:34:26 PM EDT"
     */
    private function parseJailDate($value): ?string
    {
        if (!$value) return null;

        // 1. remove ALL line breaks and normalize whitespace
        $value = preg_replace('/\R+/u', ' ', $value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = trim($value);

        // 2. remove stray quotes
        $value = trim($value, "\"'");

        try {
            return Carbon::createFromFormat(
                'm/d/y h:i:s A T',
                $value
            )->toDateTimeString();
        } catch (\Throwable $e) {
            try {
                return Carbon::parse($value)->toDateTimeString();
            } catch (\Throwable $e) {
                return null;
            }
        }
    }



    private function toMysqlDateTime($value): ?string
{
    if (empty($value)) {
        return null;
    }

    // Normalize newlines/spaces
    $value = preg_replace('/\R+/', ' ', $value);
    $value = preg_replace('/\s+/', ' ', trim($value));

    // Remove quotes around the value
    $value = trim($value, "\"'");

    // Remove stray OCR/export characters
    $value = str_replace([')', "'"], '', $value);


    

    // Fix:
    // 01:08:2 9 PM -> 01:08:29 PM
    $value = preg_replace(
        '/(\d{1,2}:\d{1,2}):(\d)\s+(\d)\s+(AM|PM)/i',
        '$1:$2$3 $4',
        $value
    );

    // Pad hour/minute/second to two digits
    $value = preg_replace_callback(
        '/(\d{1,2}):(\d{1,2}):(\d{1,2})/',
        function ($m) {
            return sprintf('%02d:%02d:%02d', $m[1], $m[2], $m[3]);
        },
        $value
    );

    // Remove trailing timezone (EST, EDT, UTC, etc.)
    $value = preg_replace('/\s+[A-Z]{2,4}$/', '', $value);

    try {
        return \Carbon\Carbon::createFromFormat(
            'm/d/y h:i:s A',
            $value
        )->format('Y-m-d H:i:s');
    } catch (\Throwable $e) {
    Log::warning('Invalid datetime', [
        'value' => $value,
        'error' => $e->getMessage(),
    ]);

    return null;
}
}

}