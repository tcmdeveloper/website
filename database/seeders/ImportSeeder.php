<?php

namespace Database\Seeders;

use App\Services\ImportService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // Only run this block outside production/staging environments.
        //
        // app()->environment('local') checks whether the current Laravel
        // environment is "local" (typically your development machine).
        //
        // If the app is NOT running locally, stop execution immediately.
        // This prevents dangerous or unnecessary imports from running
        // in shared/testing/production environments.
        
        if (! app()->environment('local')) {
            return;
        }


        // Resolve the LegacyImportService from Laravel's service container
        // and execute the import process.
        //
        // app(...) is equivalent to resolve(...) or dependency injection.
        // The service likely handles importing legacy database/application data.

        app(ImportService::class)->runImportServices();

    }

}
