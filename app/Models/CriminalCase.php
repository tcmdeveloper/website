<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CriminalCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'hex',
        'user_id',
        'name',
        'slug',
        'description',
    ];

    protected static function booted()
    {
        static::creating(function ($criminalCase) {
            if (empty($criminalCase->slug)) {
                $criminalCase->slug = Str::slug($criminalCase->name);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Route Binding (IMPORTANT)
    | This makes URLs use /criminal-cases/{slug}
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


    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Author of the criminal case
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Criminal Case
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Published articles
    public function publishedDocuments()
    {
        return $this->hasMany(Document::class)->published();
    }

    // Formatted views (25419 -> 25,419)
    protected function formattedViews(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->views),
        );
    }
}
