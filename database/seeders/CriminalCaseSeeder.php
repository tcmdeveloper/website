<?php

namespace Database\Seeders;

use App\Models\CriminalCase;
use Illuminate\Database\Seeder;

class CriminalCaseSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new CriminalCase();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'views' => $item->views,
                'user_id' => $item->user_id,
                'published_at' => $item->published_at,
                'is_published' => $item->is_published,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
        }
    }
}
