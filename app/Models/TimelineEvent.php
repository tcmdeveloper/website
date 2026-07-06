<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    public const TYPES = [
        'crime',
        'disappearance',
        'discovery',
        'arrest',
        'charge',
        'indictment',
        'hearing',
        'trial',
        'verdict',
        'sentencing',
        'appeal',
        'release',
        'death',
        'other',
    ];


    protected $fillable = [
        'hex',
        'timeline_id',
        'title',
        'description',
        'occurred_at',
        'date_label',
        'sort_order',
        'type',
        'icon',
        'color',
        'source_name',
        'source_url',
        'is_public',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'is_public' => 'boolean',
    ];



    // RELATIONSHIPS

    // Timeline
    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }
    
}
