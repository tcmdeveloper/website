<?php

namespace App\Services\Imports;

class ImportCacheService
{
    public function import(): void
    {   
        $table = 'cache';
        $uniqueBy = ['key'];

        $updateFields = [
            'key',
            'value',
            'expiration'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}