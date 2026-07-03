<?php

namespace Database\Seeders;

use App\Models\Transcription;
use Illuminate\Database\Seeder;

class TranscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new Transcription();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'user_id' => $item->user_id,
                'source_type' => $item->source_type,
                'source_url' => $item->source_url,
                'title' => $item->title,
                'original_filename' => $item->original_filename,
                'video_path' => $item->video_path,
                'audio_path' => $item->audio_path,
                'json_path' => $item->json_path,
                'srt_path' => $item->srt_path,
                'vtt_path' => $item->vtt_path,
                'duration_seconds' => $item->duration_seconds,
                'status' => $item->status,
                'progress' => $item->progress,
                'error_message' => $item->error_message,
                'started_at' => $item->started_at,
                'completed_at' => $item->completed_at,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
        }
    }
}
