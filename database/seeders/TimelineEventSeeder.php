<?php

namespace Database\Seeders;

use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;

class TimelineEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new TimelineEvent();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'timeline_id' => $item->timeline_id,
                'title' => $item->title,
                'description' => $item->description,
                'occurred_at' => $item->occurred_at,
                'date_label' => $item->date_label,
                'sort_order' => $item->sort_order,
                'type' => $item->type,
                'icon' => $item->icon,
                'color' => $item->color,
                'source_name' => $item->source_name,
                'source_url' => $item->source_url,
                'is_public	' => $item->is_public,
                'created_at' => $item->created_at,
                'updated_at	' => $item->updated_at,
            ]);
        }

    	

    }
}
