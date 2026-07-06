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
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'views',
        'user_id',
        'published_at',
        'is_published',
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

    // Latest first
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('published_at');
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

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }







    //
    // ATTRIBUTE ACCESSORS
    //
    
    // Formatted views (25419 -> 25,419)
    protected function formattedViews(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->views),
        );
    }

    // Published check
    public function isPublished(): bool
    {
        return $this->is_published && $this->published_at !== null;
    }




    // Images
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function featuredImage()
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('is_featured', true);
    }




   /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    |
    | Generate the publicly accessible URL for the stored image.
    | This allows `$model->image_url` to return a full URL instead
    | of the raw storage path saved in the database.
    |
    */
    
    protected function displayImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->featuredImage
                ?? new Image([
                    'image_path' => 'images/default-article',
                    'alt_text' => 'Default case image',
                ]),
        );
    }

}
