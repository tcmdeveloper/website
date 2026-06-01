<?php

namespace App\Jobs;

use App\Models\Transcription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class TranscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;
    public $tries = 1;

    public function __construct(
        public Transcription $transcription
    ) {}

    public function handle(): void
    {
        $t = $this->transcription;

        Log::info("===== TRANSCRIPTION START #{$t->id} =====");

        $audioDir = storage_path('app/audio');
        $outputDir = storage_path('app/subtitles');

        if (!file_exists($audioDir)) mkdir($audioDir, 0777, true);
        if (!file_exists($outputDir)) mkdir($outputDir, 0777, true);

        $filename = uniqid("audio_{$t->id}_");

        try {
            $t->update([
                'status' => 'processing',
                'progress' => 10,
            ]);

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Get audio file
            |--------------------------------------------------------------------------
            */

            $audioPath = null;

            // CASE 1: Uploaded file
            if ($t->source_type === 'upload') {
                $audioPath = storage_path('app/' . $t->file_path);
            }

            // CASE 2: YouTube download
            if ($t->source_type === 'youtube') {

                Log::info("Downloading YouTube audio...");

                $process = new Process([
                    '/opt/homebrew/bin/yt-dlp',
                    '-x',
                    '--audio-format', 'wav',
                    '--ffmpeg-location', '/opt/homebrew/bin',
                    '-o',
                    $audioDir . "/{$filename}.%(ext)s",
                    $t->source_url,
                ]);

                $process->setTimeout(600);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new \Exception($process->getErrorOutput());
                }

                $files = glob($audioDir . "/{$filename}*.wav");

                if (empty($files)) {
                    throw new \Exception("Audio file not found after download");
                }

                $audioPath = $files[0];
            }

            if (!$audioPath || !file_exists($audioPath)) {
                throw new \Exception("Audio file missing");
            }

            $t->update(['progress' => 40]);

            /*
            |--------------------------------------------------------------------------
            | STEP 2: WhisperX
            |--------------------------------------------------------------------------
            */

            Log::info("Running WhisperX...");

            $whisper = new Process([
                '/opt/homebrew/opt/python@3.11/bin/python3.11',
                '-m',
                'whisperx',
                $audioPath,
                '--model',
                'large-v3',
                '--language',
                'en',
                '--output_format',
                'json',
                '--output_dir',
                $outputDir,
                '--align_model',
                'WAV2VEC2_ASR_LARGE_LV60K_960H',
                '--compute_type',
                'int8'
            ]);

            $whisper->setTimeout(7200);
            $whisper->run();

            if (!$whisper->isSuccessful()) {
                throw new \Exception($whisper->getErrorOutput());
            }

            $t->update(['progress' => 90]);

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Save output JSON
            |--------------------------------------------------------------------------
            */

            $jsonFiles = glob($outputDir . '/*.json');
            $latest = collect($jsonFiles)->sortByDesc(fn ($f) => filemtime($f))->first();

            if (!$latest) {
                throw new \Exception("No Whisper output found");
            }

            $content = file_get_contents($latest);

            $t->update([
                'status' => 'completed',
                'progress' => 100,
                'result' => $content,
            ]);

            Log::info("===== TRANSCRIPTION COMPLETE #{$t->id} =====");

        } catch (\Throwable $e) {

            $t->update([
                'status' => 'failed',
                'progress' => 0,
            ]);

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            throw $e;
        }
    }
}