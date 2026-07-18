<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $items = Character::on('mysql_import')->get();

        foreach ($items as $item) {

            Character::create([
                'id' => $item->id,
                'slug' => $item->slug,
                'hex' => $item->hex,

                'name' => $item->name,
                'type' => $item->type,

                'date_of_birth' => $item->date_of_birth,
                'date_of_death' => $item->date_of_death,

                'gender' => $item->gender,
                'nationality' => $item->nationality,

                'description' => $item->description,
                
                'meta_title' => $item->meta_title,
                'meta_description' => $item->meta_description,

                'image_path' => $item->image_path,

                'views' => $item->views,

                'is_published' => $item->is_published,
                'published_at' => $item->published_at,

                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}