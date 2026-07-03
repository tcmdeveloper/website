<?php

namespace Database\Seeders;

use App\Models\TermsVersion;
use Illuminate\Database\Seeder;

class TermsVersionSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new TermsVersion();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'version' => $item->version,
                'content' => $item->content,
                'published_at' => $item->published_at,
                'created_at' => $item->created_at,
                'updated_at	' => $item->updated_at,
            ]);
        }
    }
}
