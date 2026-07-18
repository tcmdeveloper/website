<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

class Article extends Model
{
    use HasFactory;


    public const PER_PAGE = 12;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'hex',
        'title',
        'slug',
        'excerpt',
        'content',
        'user_id',
        'category_id',
        'featured_image',
        'published_at',
        'is_published',
        'meta_title',
        'meta_description',
        'og_image',
        'user_id',
        'category_id',
        'criminal_case_id',
        'published_at',
        'is_published',
        'views',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Route Binding (IMPORTANT)
    | This makes URLs use /articles/{slug}
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Author of the article
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Criminal Case
    public function criminalCase()
    {
        return $this->belongsTo(CriminalCase::class);
    }

    // Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }



    // Images
    public function featuredImage()
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('is_featured', true);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderBy('sort_order');
    }


    protected function displayImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->featuredImage
                ?? $this->images()->first()
                ?? new Image([
                    'image_path' => 'images/default-article',
                    'alt_text' => 'Default case image',
                ]),
        );
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

    



    // Get article HTML content for markdown
    public function getExcerptHtmlAttribute()
    {
        $converter = new CommonMarkConverter();

        return $converter
            ->convert($this->excerpt)
            ->getContent();
    }

    // Covert markdown content to HTML
    public function getContentHtmlAttribute(): string
    {
        if (blank($this->content)) {
            return '<p class="text-gray-400">No content available.</p>';
        }

        return (new CommonMarkConverter())
            ->convert($this->content)
            ->getContent();
    }

    






    // Formatted views (25419 -> 25,419)
    protected function formattedViews(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->views),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    // Only published articles
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
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
    | Model Boot Logic
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($article) {

            // Generate slug if missing
            if (empty($article->slug)) {
                $baseSlug = Str::slug($article->title);
                $slug = $baseSlug;
                $count = 1;

                while (self::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $article->slug = $slug;
            }

            // Generate unique 11-char HEX public ID
            if (empty($article->hex)) {
                do {
                    $article->hex = Str::upper(Str::random(11));
                } while (self::where('hex', $article->hex)->exists());
            }

            // Default publish state (optional safety)
            if (is_null($article->is_published)) {
                $article->is_published = false;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    // Increment views safely
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    // Reading time estimate (simple version)
    public function getReadingTimeAttribute(): string
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = max(1, ceil($words / 200));

        return "{$minutes} min read";
    }

    // Published check
    public function isPublished(): bool
    {
        return $this->is_published && $this->published_at !== null;
    }
}