<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JailCallLog extends Model
{
    protected $fillable = [
        'site',
        'term_group_name',
        'term_name',
        'raw_start_time',
        'start_time',
        'start_time_error',
        'raw_end_time',
        'end_time',
        'end_time_error',
        'duration',
        'service_type',
        'comm_type',
        'comm_status',
        'termination_category',
        'first_name',
        'last_name',
        'account_number',
        'pin',
        'other_party',
        'is_private',
        'language',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'private'    => 'boolean',
    ];
}