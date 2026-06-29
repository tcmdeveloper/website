<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);

        }


    }


}
