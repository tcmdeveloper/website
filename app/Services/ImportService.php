<?php

namespace App\Services;

use App\Services\Imports\ImportCacheLocksService;
use App\Services\Imports\ImportCacheService;
use App\Services\Imports\ImportChangeEmailRequestsService;
use App\Services\Imports\ImportFailedJobsService;
use App\Services\Imports\ImportJobBatchesService;
use App\Services\Imports\ImportJobsService;
use App\Services\Imports\ImportMigrationsService;
use App\Services\Imports\ImportPasswordResetTokensService;
use App\Services\Imports\ImportSessionsService;
use App\Services\Imports\ImportTermsVersionsService;
use App\Services\Imports\ImportUsersService;
use Illuminate\Support\Facades\DB;

class ImportService
{
    public function runImportServices(): void
    {
        
        app(ImportCacheService::class)->import();
        app(ImportCacheLocksService::class)->import();
        app(ImportChangeEmailRequestsService::class)->import();
        app(ImportFailedJobsService::class)->import();
        app(ImportJobsService::class)->import();
        app(ImportJobBatchesService::class)->import();
        app(ImportMigrationsService::class)->import();
        app(ImportPasswordResetTokensService::class)->import();
        app(ImportSessionsService::class)->import();
        app(ImportTermsVersionsService::class)->import();
        app(ImportUsersService::class)->import();
    }
}