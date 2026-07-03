<?php

namespace Database\Seeders;

use App\Models\JailCallLog;
use Illuminate\Database\Seeder;

class JailCallLogSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new JailCallLog();

        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'site' => $item->site,
                'term_group_name' => $item->term_group_name,
                'term_name' => $item->term_name,
                'raw_start_time' => $item->raw_start_time,
                'start_time' => $item->start_time,
                'start_time_error' => $item->start_time_error,
                'raw_end_time' => $item->raw_end_time,
                'end_time' => $item->end_time,
                'end_time_error' => $item->end_time_error,
                'duration' => $item->duration,
                'service_type' => $item->service_type,
                'comm_type' => $item->comm_type,
                'comm_status' => $item->comm_status,
                'termination_category' => $item->termination_category,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'account_number' => $item->account_number,
                'pin' => $item->pin,
                'other_party' => $item->other_party,
                'is_private' => $item->is_private,
                'language' => $item->language,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}
