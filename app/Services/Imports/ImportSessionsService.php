<?php

namespace App\Services\Imports;

class ImportSessionsService
{
    public function import(): void
    {
        $table = 'sessions';
        $uniqueBy = ['id'];

        $updateFields = [
            'id',
            'user_id',
            'ip_address',
            'user_agent',
            'payload',
            'last_activity'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}