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

    public function imageable()
    {
        return $this->morphTo();
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute(): string
    {

        return Storage::url("{$this->image_path}.jpg");
    }


    public function url(?int $width = null, string $extension = 'jpg'): string
    {
        $path = $this->image_path;

        if ($width !== null) {
            $path .= "-{$width}";
        }

        return Storage::url(
            "{$path}.{$extension}"
        );
    }

    

    public function responsiveSizes(): array
{
    return [
        160,
        320,
        480,
        640,
        800,
        1200,
    ];
}

    
}