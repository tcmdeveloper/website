<?php

namespace Database\Seeders;

use App\Models\FailedJob;
use Illuminate\Database\Seeder;

class FailedJobSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new FailedJob();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'uuid' => $item->uuid,
                'connection' => $item->connection,
                'queue' => $item->queue,
                'payload' => $item->payload,
                'exception' => $item->exception,
                'failed_at' => $item->failed_at,
            ]);
        }
    }
}
