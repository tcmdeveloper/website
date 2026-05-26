<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsVersion extends Model
{
    
    // -----------------------------------------------------
    // MASS ASSIGNABLE ATTRIBUTES FOR TERMSVERSION MODEL
    // -----------------------------------------------------

    protected $fillable = [
        'version',
        'content',
        'published_at',
    ];


    // -----------------------------------------------------
    // ATTRIBUTE CASTING FOR THE TERMSVERSION MODEL
    // -----------------------------------------------------

    protected $casts = [
        'published_at' => 'datetime',
    ];


    // -----------------------------------------------------
    // GET THE CURRENT ACTIVE TERMS VERSION
    // -----------------------------------------------------

    public static function current()
    {
        return static::whereNotNull('published_at')
            ->latest('published_at')
            ->first();
    }


}