<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JailCallLogController extends Controller
{
    private const BATCH_SIZE = 1000;

    public function showForm()
    {
        return view('imports.jail-call-logs');
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

//         dd([
//     'raw_start' => preg_replace('/\R+/u', ' ', $row[3]) ?? null,
//     'hex' => isset($row[3]) ? bin2hex($row[3]) : null,
// ]); 
            // Skip invalid rows safely
            if (!is_array($row) || count($row) < 17) {
                continue;
            }

            $batch[] = [
                // POSITIONS (based on your real dump)
                'site' => trim($row[0] ?? ''),
                'term_group_name' => trim($row[1] ?? ''),
                'term_name' => trim($row[2] ?? ''),

                // MULTILINE DATE FIX
                'start_time' => $this->toMysqlDateTime($row[3] ?? null),
                'end_time' => $this->toMysqlDateTime($row[4] ?? null),

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
    if (!$value) return null;

    // 1. normalize whitespace/newlines
    $value = preg_replace('/\R+/', ' ', $value);
    $value = preg_replace('/\s+/', ' ', $value);
    $value = trim($value, "\"'");

    // 2. fix broken seconds like "03:34:2 6"
    $value = preg_replace('/:(\d)\s+(\d)/', ':$1$2', $value);

    // 3. remove timezone (EDT, UTC, etc.)
    $value = preg_replace('/\s[A-Z]{2,4}$/', '', $value);

    try {
        return \Carbon\Carbon::createFromFormat(
            'm/d/y h:i:s A',
            $value
        )->format('Y-m-d H:i:s');
    } catch (\Throwable $e) {
        // fallback (very important for dirty data)
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }
}

}