<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ArticleImage extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'article_id',
        'path',
        'caption',
        'source',
        'source_url',
        'alt_text',
        'is_featured',
        'sort_order',
    ];


    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function article()
    {
        return $this->belongsTo(Article::class);
    }


    public function getImageUrlAttribute(): string
    {
        foreach (['avif', 'webp', 'jpg'] as $extension) {

            $path = "{$this->path}.{$extension}";

            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }

        return '';
    }

    
}