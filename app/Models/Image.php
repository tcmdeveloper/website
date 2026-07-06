<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'hex',
        'imageable_type',
        'imageable_id',
        'image_path',
        'alt_text',
        'caption',
        'credit_name',
        'credit_url',
        'is_featured',
        'sort_order',
        'user_id',
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
    | Model Events
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::creating(function ($image) {
            if (blank($image->hex)) {
                $image->hex = Str::random(11);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function article()
    {
        return $this->belongsTo(Article::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                foreach (['avif', 'webp', 'jpg'] as $extension) {
                    $path = "{$this->image_path}.{$extension}";

                    if (Storage::disk('public')->exists($path)) {
                        return Storage::url($path);
                    }
                }

                // Fallback for images stored in /public
                foreach (['avif', 'webp', 'jpg'] as $extension) {
                    $path = public_path("{$this->image_path}.{$extension}");

                    if (file_exists($path)) {
                        return asset("{$this->image_path}.{$extension}");
                    }
                }

                return '';
            }
        );
    }

    
}