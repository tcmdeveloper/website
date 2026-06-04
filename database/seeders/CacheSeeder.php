<?php

namespace Database\Seeders;

use App\Models\Cache;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CacheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // SEED FROM IMPORT DATABASE

        $model = new Cache();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'key' => $item->id,
                'value' => $item->value,
                'expiration' => $item->expiration
            ]);

        }


    }


}
