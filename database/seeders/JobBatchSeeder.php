<?php

namespace Database\Seeders;

use App\Models\JobBatch;
use Illuminate\Database\Seeder;

class JobBatchSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new JobBatch();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'name' => $item->name,
                'total_jobs' => $item->total_jobs,
                'pending_jobs' => $item->pending_jobs,
                'failed_jobs' => $item->failed_jobs,
                'failed_job_ids' => $item->failed_job_ids,
                'options' => $item->options,
                'cancelled_at' => $item->cancelled_at,
                'created_at' => $item->created_at,
                'finished_at' => $item->finished_at,
            ]);
        }
    }
}
