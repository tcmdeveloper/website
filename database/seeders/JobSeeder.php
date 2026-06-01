<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // SEED FROM IMPORT DATABASE

        $model = new Job();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'queue' => $item->queue,
                'payload' => $item->payload,
                'attempts' => $item->attempts,
                'reserved_at' => $item->reserved_at,
                'available_at' => $item->available_at,
                'created_at' => $item->created_at,
            ]);

        }


    }


}
