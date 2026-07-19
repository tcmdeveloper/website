<?php

namespace App\Models;

use App\Enums\TimelineType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'sort_order',
        'views',
        'is_default',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_published' => 'boolean',
        'type' => TimelineType::class,
        'published_at' => 'datetime',
    ];



    
    protected static function booted()
    {
        static::creating(function ($timeline) {
            if (empty($timeline->slug)) {
                $timeline->slug = Str::slug($timeline->name);
            }
        });
    }




    /*
    |--------------------------------------------------------------------------
    | Route Binding (IMPORTANT)
    | This makes URLs use /timlines/{slug}
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }



    // -----------------------------------------------------
    // SCOPES
    // -----------------------------------------------------

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // Latest first
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('published_at');
    }




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





    // ATTRIBUTE ACCESSORS

    // Formatted views (25419 -> 25,419)
    protected function formattedViews(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => number_format($attributes['views']),
        );
    }

    // Published check
    public function isPublished(): bool
    {
        return $this->is_published && $this->published_at !== null;
    }


}
