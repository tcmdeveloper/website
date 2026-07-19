<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriminalCaseCourtIdentifier extends Model
{
    protected $fillable = [
        'criminal_case_id',
        'provider',
        'key',
        'value',
    ];


    public function criminalCase(): BelongsTo
    {
        return $this->belongsTo(
            CriminalCase::class
        );
    }
}