<?php

namespace App\Services\Imports;

class ImportCacheLocksService
{
    public function import(): void
    {
        $table = 'cache_locks';
        $uniqueBy = ['key'];

        $updateFields = [
            'key',
            'owner',
            'expiration'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}