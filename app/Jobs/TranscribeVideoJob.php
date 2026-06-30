<?php

namespace App\Jobs;

use App\Models\TranscriptSegment;
use App\Models\Video;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class TranscribeVideoJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Video $video
    ) {}

    public function handle(): void
    {
        $this->video->update([
            'status' => 'transcribing',
        ]);

        $videoPath = storage_path('app/public/' . $this->video->filename);

        $audioDir = storage_path('app/public/audio');
        $transcriptDir = storage_path('app/public/transcripts');

        if (!is_dir($audioDir)) {
            mkdir($audioDir, 0755, true);
        }

        if (!is_dir($transcriptDir)) {
            mkdir($transcriptDir, 0755, true);
        }

        $audioPath = $audioDir . '/' . $this->video->youtube_id . '.wav';
        $jsonPath = $transcriptDir . '/' . $this->video->youtube_id . '.json';

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Extract audio with FFmpeg
            |--------------------------------------------------------------------------
            */

            $ffmpeg = new Process([
                'ffmpeg',
                '-y',
                '-i',
                $videoPath,
                '-vn',
                '-acodec',
                'pcm_s16le',
                '-ar',
                '16000',
                '-ac',
                '1',
                $audioPath,
            ]);

            $ffmpeg->setTimeout(null);
            $ffmpeg->mustRun();

            /*
            |--------------------------------------------------------------------------
            | 2. Run WhisperX transcription
            |--------------------------------------------------------------------------
            */

            $whisper = new Process([
                'whisperx',
                $audioPath,
                '--output_format',
                'json',
                '--output_dir',
                $transcriptDir,
            ]);

            $whisper->setTimeout(null);
            $whisper->mustRun();

            /*
            |--------------------------------------------------------------------------
            | 3. Load JSON result
            |--------------------------------------------------------------------------
            */

            $generatedJson = $transcriptDir . '/' . pathinfo($audioPath, PATHINFO_FILENAME) . '.json';

            if (!file_exists($generatedJson)) {
                throw new \RuntimeException('Transcript JSON not found.');
            }

            $transcript = json_decode(file_get_contents($generatedJson), true);






            // Insert into transript_segments

            foreach ($transcript['segments'] as $segment) {
                TranscriptSegment::create([
                    'video_id' => $this->video->id,
                    'start' => $segment['start'],
                    'end' => $segment['end'],
                    'text' => $segment['text'],
                ]);
            }












            // Generate SRT file

            $srtInput = $generatedJson;
            $srtOutput = storage_path('app/public/subtitles/' . $this->video->youtube_id . '.srt');

            $srtDir = dirname($srtOutput);

            if (!is_dir($srtDir)) {
                mkdir($srtDir, 0755, true);
            }


            $process = new Process([
                'python3',
                base_path('app/Services/Transcription/json_to_srt.py'),
                $srtInput,
                $srtOutput,
            ]);

            $process->setTimeout(null);
            $process->mustRun();







            /*
            |--------------------------------------------------------------------------
            | 4. Save to database
            |--------------------------------------------------------------------------
            */

            $this->video->update([
                'transcript' => json_encode($transcript),
                'status' => 'completed',
            ]);

        } catch (\Throwable $e) {

            Log::error('Transcription failed', [
                'video_id' => $this->video->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->video->update([
                'status' => 'failed',
            ]);

            throw $e;
        }
    }
}