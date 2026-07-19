<?php

namespace Database\Seeders;

use App\Models\CriminalCaseCourtIdentifier;
use Illuminate\Database\Seeder;

class CriminalCaseCourtIdentifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new CriminalCaseCourtIdentifier();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'criminal_case_id' => $item->criminal_case_id,
                'provider' => $item->provider,
                'key' => $item->key,
                'value' => $item->value,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,

            ]);
        }

    }
}
