<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    
    protected $fillable = [
        'slug',
        'hex',

        'name',
        'type',

        'date_of_birth',
        'date_of_death',

        'gender',
        'nationality',

        'summary',
        'biography',

        'meta_title',
        'meta_description',

        'image_path',

        'views',

        'is_published',
        'published_at',
    ];


    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_death' => 'date',

        'is_published' => 'boolean',

        'published_at' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];





    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // CRIMINAL CASES

    public function criminalCases()
    {
        return $this->belongsToMany(CriminalCase::class)
            ->withPivot('role')
            ->withTimestamps();
    }


    // FEATURED IMAGE

    public function featuredImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('is_featured', true);
    }


    // IMAGES
    
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }




    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTE ACCESSORS
    |--------------------------------------------------------------------------
    */

    // DISPLAY IMAGE

    protected function displayImage(): Attribute
    {
        return Attribute::make(get: fn () => $this->featuredImage
                ?? $this->images()->first()
                ?? new Image([
                    'image_path' => 'images/default-article',
                    'alt_text' => 'Default character image',
                    'is_optimized' => false,
                ]),
        );
    }

    



}
