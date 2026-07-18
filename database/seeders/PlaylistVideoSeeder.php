<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaylistVideoSeeder extends Seeder
{
    public function run(): void
    {
        $items = DB::connection('mysql_import')
            ->table('playlist_video')
            ->get();

        foreach ($items as $item) {

            DB::table('playlist_video')->insert([
                'playlist_id' => $item->playlist_id,
                'video_id' => $item->video_id,
                'position' => $item->position,

                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}