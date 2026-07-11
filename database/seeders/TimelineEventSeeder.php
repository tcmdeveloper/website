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

        $items = TimelineEvent::on('mysql_import')->get();

        /*
        |--------------------------------------------------------------------------
        | Pass 1: Create all events without parents
        |--------------------------------------------------------------------------
        */

        foreach ($items as $item) {

            TimelineEvent::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'timeline_id' => $item->timeline_id,

                // Ignore parent for now
                'parent_event_id' => null,

                'title' => $item->title,
                'description' => $item->description,
                'occurred_at' => $item->occurred_at,
                'date_label' => $item->date_label,
                'time_label' => $item->time_label,
                'sort_order' => $item->sort_order,
                'type' => $item->type,
                'icon' => $item->icon,
                'color' => $item->color,
                'source_name' => $item->source_name,
                'source_url' => $item->source_url,
                'is_published' => $item->is_published,
                'published_at' => $item->published_at,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pass 2: Attach parents
        |--------------------------------------------------------------------------
        */

        foreach ($items as $item) {

            if (! $item->parent_event_id) {
                continue;
            }

            TimelineEvent::where('id', $item->id)
                ->update([
                    'parent_event_id' => $item->parent_event_id,
                ]);
        }
    }
}
