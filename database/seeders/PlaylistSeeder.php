<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Seeder;

class PlaylistSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $items = Playlist::on('mysql_import')->get();

        foreach ($items as $item) {

            Playlist::create([
                'id' => $item->id,
                'hex' => $item->hex,

                'name' => $item->name,
                'slug' => $item->slug,

                'description' => $item->description,

                'thumbnail' => $item->thumbnail,

                'views' => $item->views,

                'user_id' => $item->user_id,

                'is_published' => $item->is_published,
                'published_at' => $item->published_at,

                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}