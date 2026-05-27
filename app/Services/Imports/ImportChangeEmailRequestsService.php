<?php

namespace App\Services\Imports;

class ImportChangeEmailRequestsService
{
    public function import(): void
    {
        $table = 'email_change_requests';
        $uniqueBy = ['id'];

        $updateFields = [
            'id',
            'user_id',
            'old_email',
            'new_email',
            'token',
            'status',
            'verified_at',
            'expires_at',
            'cancelled_at',
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