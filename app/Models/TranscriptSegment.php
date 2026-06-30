<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranscriptSegment extends Model
{
    protected $fillable = [
        'video_id',
        'start',
        'end',
        'text',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}