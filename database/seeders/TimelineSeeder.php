<?php

namespace Database\Seeders;

use App\Models\Timeline;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new Timeline();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'criminal_case_id' => $item->criminal_case_id,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'type' => $item->type,
                'icon' => $item->icon,
                'color' => $item->color,
                'is_default' => $item->is_default,
                'is_public' => $item->is_public,
                'sort_order' => $item->sort_order,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }

    	

    }
}
