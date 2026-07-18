<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
        'criminal_case_number',
        'arrest_date',
        'clerk_qs',
        'last_docket_sync_at',
        'judge_id',
        'views',
        'user_id',
        'published_at',
        'is_published',
    ];


    protected $casts = [
        'arrest_date' => 'date',
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

    // AUTHOR

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
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


    // CRIMINAL CASE

    public function articles()
    {
        return $this->hasMany(Article::class);
    }


    // DOCUMENTS

    public function documents()
    {
        return $this->hasMany(Document::class);
    }


    // PUBLISHED DOCUMENTS

    public function publishedDocuments()
    {
        return $this->hasMany(Document::class)->published();
    }


    // TIMELINES

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }



    // DOCKET ENTRIES

    public function docketEntries()
    {
        return $this->hasMany(DocketEntry::class, 'criminal_case_id');
    }


    // CHARACTERS

    public function characters()
    {
        return $this->belongsToMany(Character::class)
            ->withPivot('role')
            ->withTimestamps();
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
                    'alt_text' => 'Default case image',
                    'is_optimized' => false,
                ]),
        );
    }
    
    
    // FORMATED VIEWS

    protected function formattedViews(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->views),
        );
    }

    
    // IS PUBLISHED

    public function isPublished(): bool
    {
        return $this->is_published && $this->published_at !== null;
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

    public function getImageUrlAttribute(): string
    {
        return $this->url();
    }


    public function url(
        ?int $width = 640,
        string $extension = 'avif'
    ): string {
        $path = $this->image_path;

        if ($width !== null) {
            $path .= "-{$width}";
        }

        return Storage::url(
            "{$path}.{$extension}"
        );
        
    }




}
