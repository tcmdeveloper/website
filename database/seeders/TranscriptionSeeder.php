<?php

namespace Database\Seeders;

use App\Models\Transcription;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TranscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // SEED FROM IMPORT DATABASE

        $model = new Transcription();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->id,
                'user_id' => $item->id,
                'source_type' => $item->id,
                'source_url' => $item->id,
                'title' => $item->id,
                'original_filename' => $item->id,
                'video_path' => $item->id,
                'audio_path' => $item->id,
                'json_path' => $item->id,
                'srt_path' => $item->id,
                'vtt_path' => $item->id,
                'duration_seconds' => $item->id,
                'status' => $item->id,
                'progress' => $item->id,
                'error_message' => $item->id,
                'started_at' => $item->id,
                'completed_at' => $item->id,
                'created_at' => $item->id,
                'updated_at	' => $item->id
            ]);

        }


    }


}
