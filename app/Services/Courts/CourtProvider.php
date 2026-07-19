<?php

namespace App\Services\Courts;

use App\Models\CriminalCase;
use App\Models\DocketEntry;
use App\Models\Document;

interface CourtProvider
{
    public function getDockets(
        CriminalCase $criminalCase
    ): array;


    public function downloadDocument(
        DocketEntry $docketEntry,
        int $userId
    ): Document;
}