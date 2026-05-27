<?php

namespace App\Services\Imports;

class ImportFailedJobsService
{
    public function import(): void
    {
        $table = 'failed_jobs';
        $uniqueBy = ['id'];

        $updateFields = [
            'id',
            'uuid',
            'connection',
            'queue',
            'payload',
            'exception',
            'failed_at'
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}