<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{

    protected $fillable = [
        'hex',
        'criminal_case_id',
        'name',
        'slug',
        'description',
        'type',
        'icon',
        'color',
        'is_default',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_public' => 'boolean',
    ];


    // RELATIONSHIPS

    // Criminal Case
    public function criminalCase()
    {
        return $this->belongsTo(CriminalCase::class);
    }

    // Timeline Events
    public function events()
    {
        return $this->hasMany(TimelineEvent::class)
            ->orderBy('occurred_at')
            ->orderBy('sort_order');
    }
}
