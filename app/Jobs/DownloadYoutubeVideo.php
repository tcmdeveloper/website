<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DownloadYoutubeVideo implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Video $video
    ) {
    }

    public function handle(): void
    {
        $this->video->update([
            'status' => 'downloading',
        ]);

        $videoDir = storage_path('app/public/videos');

        if (! is_dir($videoDir)) {
            mkdir($videoDir, 0755, true);
        }

        try {
            /*
             * ---------------------------------------------------------
             * Get metadata
             * ---------------------------------------------------------
             */

            $metadataProcess = new Process([
                'yt-dlp',
                '--dump-single-json',
                $this->video->youtube_url,
            ]);

            $metadataProcess->mustRun();

            $metadata = json_decode(
                $metadataProcess->getOutput(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            
            /*
             * ---------------------------------------------------------
             * Download video
             * ---------------------------------------------------------
             */

            $outputTemplate = $videoDir . '/%(id)s.%(ext)s';
            

            $downloadProcess = new Process([
                'yt-dlp',
                '-f',
                'bv*[ext=mp4]+ba[ext=m4a]/b[ext=mp4]/best',
                '-o',
                $outputTemplate,
                $this->video->youtube_url,
            ]);

            $downloadProcess->setTimeout(null);

            $downloadProcess->mustRun();

            /*
             * ---------------------------------------------------------
             * Find downloaded file
             * ---------------------------------------------------------
             */

            $files = glob($videoDir . '/' . $metadata['id'] . '.*');

            if (empty($files)) {
                throw new \RuntimeException('Downloaded file not found.');
            }

            $filename = basename($files[0]);





            // Download the thumbnail for this video

            $thumbDir = storage_path('app/public/thumbnails');

            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }

            $thumbProcess = new Process([
                'yt-dlp',
                '--skip-download',
                '--write-thumbnail',
                '--convert-thumbnails',
                'jpg',
                '-o',
                $thumbDir . '/%(id)s.%(ext)s',
                $this->video->youtube_url,
            ]);

            $thumbProcess->setTimeout(null);
            $thumbProcess->mustRun();


            $thumbFiles = glob($thumbDir . '/' . $metadata['id'] . '.*');

            if (!empty($thumbFiles)) {
                $thumbnailFilename = basename($thumbFiles[0]);
            } else {
                $thumbnailFilename = null;
            }








            /*
             * ---------------------------------------------------------
             * Save database
             * ---------------------------------------------------------
             */

            $this->video->update([
                'youtube_id' => $metadata['id'],
                'title' => $metadata['title'] ?? null,
                'description' => $metadata['description'] ?? null,
                'duration' => $metadata['duration'] ?? null,
                'filename' => 'videos/' . $filename,
                'thumbnail' => 'thumbnails/' . $thumbnailFilename,
                'status' => 'completed',
                'error_message' => null,
                'uploader' => $metadata['uploader'] ?? null,
                'uploader_id' => $metadata['uploader_id'] ?? null,
                'channel_url' => $metadata['channel_url'] ?? null,

            ]);
        } catch (\Throwable $e) {

            Log::error($e);

            $this->video->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}