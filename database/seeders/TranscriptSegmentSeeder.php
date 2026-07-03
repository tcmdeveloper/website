<?php

namespace Database\Seeders;

use App\Models\TranscriptSegment;
use Illuminate\Database\Seeder;

class TranscriptSegmentSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new TranscriptSegment();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'video_id' => $item->video_id,
                'start' => $item->start,
                'end' => $item->end,
                'text' => $item->text,
                'user_id' => $item->user_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}
