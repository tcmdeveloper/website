<?php

namespace App\Services\Imports;

class ImportJobBatchesService
{
    public function import(): void
    {
        $table = 'job_batches';
        $uniqueBy = ['id'];

        $updateFields = [
            'id',
            'name',
            'total_jobs',
            'pending_jobs',
            'failed_jobs',
            'failed_job_ids',
            'options',
            'cancelled_at',
            'created_at',
            'finished_at'	
        ];

        app(TableImporter::class)->import(
            table: $table,
            uniqueBy: $uniqueBy,
            updateFields: $updateFields
        );
        
    }
    
}