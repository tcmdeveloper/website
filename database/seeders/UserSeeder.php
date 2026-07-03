<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE
    
        $model = new User();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'email' => $item->email,
                'email_verified_at' => $item->email_verified_at,
                'password' => $item->password,
                'google_id' => $item->google_id,
                'remember_token' => $item->remember_token,
                'role' => $item->role,
                'username' => $item->username,
                'display_name' => $item->display_name,
                'name' => $item->name,
                'bio' => $item->bio,
                'avatar' => $item->avatar,
                'country_code' => $item->country_code,
                'state_code' => $item->state_code,
                'terms_accepted_at' => $item->terms_accepted_at,
                'terms_version' => $item->terms_version,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}
