<?php

namespace Database\Seeders;

use App\Models\EmailChangeRequest;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmailChangeRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // SEED FROM IMPORT DATABASE

        $model = new EmailChangeRequest();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'user_id' => $item->user_id,
                'old_email' => $item->old_email,
                'new_email' => $item->new_email,
                'token' => $item->token,
                'status' => $item->status,
                'verified_at' => $item->verified_at,
                'expires_at' => $item->expires_at,
                'cancelled_at' => $item->cancelled_at,
                'created_at' => $item->created_at,
                'updated_at	' => $item->updated_at
            ]);

        }


    }


}
