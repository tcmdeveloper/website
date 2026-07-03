<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new Video();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'youtube_url' => $item->youtube_url,
                'youtube_id' => $item->youtube_id,
                'title' => $item->title,
                'description' => $item->description,
                'duration' => $item->duration,
                'filename' => $item->filename,
                'thumbnail' => $item->thumbnail,
                'status' => $item->status,
                'uploader' => $item->uploader,
                'uploader_id' => $item->uploader_id,
                'channel_url' => $item->channel_url,
                'transcript' => $item->transcript,
                'user_id' => $item->user_id,
                'category_id' => $item->category_id,
                'criminal_case_id' => $item->criminal_case_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
        }
    }
}
