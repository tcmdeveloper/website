<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    
    protected $fillable = [
        'hex',
        'slug',
        'name',
        'description',
        'thumbnail',
        'views',
        'user_id',
        'is_published',
        'published_at',
    ];


    protected $casts = [
        'views' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];




    public function criminalCases()
    {
        return $this->belongsToMany(CriminalCase::class)
            ->withPivot('position')
            ->withTimestamps();
    }


    public function videos()
    {
        return $this->belongsToMany(Video::class)
            ->withPivot('position')
            ->withTimestamps()
            ->orderBy('playlist_video.position');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
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
