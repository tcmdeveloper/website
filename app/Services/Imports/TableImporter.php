<?php

namespace App\Services\Imports;

use Illuminate\Support\Facades\DB;

class TableImporter
{
    public function import(
        string $table,
        array $uniqueBy,
        array $updateFields,
        string $sourceConnection = 'mysql_import',
        string $targetConnection = 'mysql'
    ): void {
        
        $data = DB::connection($sourceConnection)
            ->table($table)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->toArray();

        DB::connection($targetConnection)
            ->table($table)
            ->upsert($data, $uniqueBy, $updateFields);

    }

}