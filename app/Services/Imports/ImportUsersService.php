<?php

namespace App\Services\Imports;

class ImportUsersService
{
    public function import(): void
    {
        $table = 'users';
        $uniqueBy = ['hex'];

        $updateFields = [
            'id',
            'hex',
            'email',
            'email_verified_at',
            'password',
            'google_id',
            'remember_token',
            'role',
            'username',
            'display_name',
            'first_name',
            'last_name',
            'avatar',
            'country_code',
            'state_code',
            'terms_accepted_at',
            'terms_version',
            'created_at',
            'updated_at'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}