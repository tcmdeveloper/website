<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterCriminalCaseSeeder extends Seeder
{
    public function run(): void
    {
        $items = DB::connection('mysql_import')
            ->table('character_criminal_case')
            ->get();

        foreach ($items as $item) {

            DB::table('character_criminal_case')->insert([
                'character_id' => $item->character_id,
                'criminal_case_id' => $item->criminal_case_id,
                'role' => $item->role,

                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}