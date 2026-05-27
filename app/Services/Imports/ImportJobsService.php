<?php

namespace App\Services\Imports;

class ImportJobsService
{
    public function import(): void
    {
        $table = 'jobs';
        $uniqueBy = ['id'];

        $updateFields = [
            'id',
            'queue',
            'payload',
            'attempts',
            'reserved_at',
            'available_at',
            'created_at'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}