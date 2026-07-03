<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'uuid',
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at',
    ];
}