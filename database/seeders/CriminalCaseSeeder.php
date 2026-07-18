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
                'meta_title' => $item->meta_title,
                'meta_description' => $item->meta_description,
                'criminal_case_number' => $item->criminal_case_number,
                'arrest_date' => $item->arrest_date,
                'clerk_qs' => $item->clerk_qs,
                'last_docket_sync_at' => $item->last_docket_sync_at,
                'views' => $item->views,
                'user_id' => $item->user_id,
                'judge_id' => $item->judge_id,
                'published_at' => $item->published_at,
                'is_published' => $item->is_published,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}
