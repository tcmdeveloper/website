<?php

namespace App\Services\Imports;

class ImportPasswordResetTokensService
{
    public function import(): void
    {
        $table = 'password_reset_tokens';
        $uniqueBy = ['email'];

        $updateFields = [
            'email',
            'token',
            'created_at'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}