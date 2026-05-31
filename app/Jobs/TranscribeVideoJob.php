<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class TranscribeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200; // 2 hours
    public $tries = 1;

    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function handle(): void
    {
        Log::info('===== TRANSCRIPTION JOB STARTED =====');
        Log::info('URL: ' . $this->url);

        $audioDir = storage_path('app/audio');
        $outputDir = storage_path('app/subtitles');

        if (!file_exists($audioDir)) {
            mkdir($audioDir, 0777, true);
        }

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $filename = uniqid('audio_');

        Log::info('Audio directory: ' . $audioDir);
        Log::info('Output directory: ' . $outputDir);
        Log::info('Filename: ' . $filename);

        try {

            /*
             |--------------------------------------------------------------------------
             | STEP 1: Download audio with yt-dlp
             |--------------------------------------------------------------------------
             */

            Log::info('Starting yt-dlp download...');

            $yt = new Process([
                '/opt/homebrew/bin/yt-dlp',
                '--cookies', storage_path('app/youtube-cookies.txt'),
                '-x',
                '--audio-format', 'wav',
                '--ffmpeg-location', '/opt/homebrew/bin',
                '--postprocessor-args',
                'ffmpeg:-ac 1 -ar 16000 -c:a pcm_s16le',
                '-o',
                $audioDir . "/{$filename}.%(ext)s",
                $this->url
            ]);

            $yt->setTimeout(600);

            $yt->run();

            Log::info('yt-dlp stdout:', [
                'output' => $yt->getOutput()
            ]);

            Log::info('yt-dlp stderr:', [
                'error' => $yt->getErrorOutput()
            ]);

            if (!$yt->isSuccessful()) {
                throw new \Exception(
                    "yt-dlp failed:\n" .
                    $yt->getErrorOutput()
                );
            }

            Log::info('yt-dlp completed successfully');

            /*
             |--------------------------------------------------------------------------
             | Find WAV file
             |--------------------------------------------------------------------------
             */

            $wavFiles = glob($audioDir . '/' . $filename . '*.wav');

            Log::info('Found wav files:', [
                'files' => $wavFiles
            ]);

            if (empty($wavFiles)) {
                throw new \Exception(
                    'No WAV file found after yt-dlp conversion'
                );
            }

            $audioPath = $wavFiles[0];

            Log::info('Audio path: ' . $audioPath);

            if (!file_exists($audioPath)) {
                throw new \Exception(
                    'Audio file does not exist: ' . $audioPath
                );
            }

            Log::info('Audio file size: ' . filesize($audioPath));

            /*
             |--------------------------------------------------------------------------
             | STEP 2: WhisperX
             |--------------------------------------------------------------------------
             */

            Log::info('Starting WhisperX...');

            $whisper = new Process([
                '/opt/homebrew/opt/python@3.11/bin/python3.11',
                '-m',
                'whisperx',
                $audioPath,
                '--model',
                'large-v3',
                '--output_format',
                'json',
                '--output_dir',
                $outputDir,
            ]);

            $whisper->setTimeout(7200);

            $whisper->setEnv([
                'PATH' => '/opt/homebrew/bin:/usr/local/bin:/usr/bin:/bin'
            ]);

            $whisper->run();

            Log::info('WhisperX stdout:', [
                'output' => $whisper->getOutput()
            ]);

            Log::info('WhisperX stderr:', [
                'error' => $whisper->getErrorOutput()
            ]);

            if (!$whisper->isSuccessful()) {
                throw new \Exception(
                    "WhisperX failed:\n" .
                    $whisper->getErrorOutput()
                );
            }

            Log::info('WhisperX completed successfully');

            Log::info('===== TRANSCRIPTION JOB FINISHED =====');

        } catch (\Throwable $e) {

            Log::error('===== JOB FAILED =====');

            Log::error($e->getMessage());

            Log::error($e->getTraceAsString());

            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('===== LARAVEL FAILED() METHOD =====');

        Log::error($e->getMessage());

        Log::error($e->getTraceAsString());
    }
}