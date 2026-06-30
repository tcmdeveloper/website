<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'hex',
        'youtube_url',
        'youtube_id',
        'title',
        'description',
        'duration',
        'filename',
        'thumbnail',
        'status',
        'uploader',
        'uploader_id',
        'channel_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Route Binding (IMPORTANT)
    | This makes URLs use /articles/{slug}
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'hex';
    }




    public function getDurationFormattedAttribute(): ?string
    {
        if (! $this->duration) {
            return null;
        }

        return gmdate('H:i:s', $this->duration);
    }




    // RELATIONSHIPS

    // Transcript segments
    public function transcriptSegments()
    {
        return $this->hasMany(TranscriptSegment::class);
    }
}
