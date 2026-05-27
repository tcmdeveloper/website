<?php

namespace App\Services\Imports;

class ImportTermsVersionsService
{
    public function import(): void
    {
        $table = 'terms_versions';
        $uniqueBy = ['id'];

        $updateFields = [
            'id',
            'version',
            'content',
            'published_at',
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