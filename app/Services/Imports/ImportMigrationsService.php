<?php

namespace App\Services\Imports;

class ImportMigrationsService
{
    public function import(): void
    {
        $table = 'migrations';
        $uniqueBy = ['id'];

        $updateFields = [
            'id',
            'migration',
            'batch'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}