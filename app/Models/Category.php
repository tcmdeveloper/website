<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'hex',
        'name',
        'slug',
        'description',
        'color'
    ];

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Route Binding (IMPORTANT)
    | This makes URLs use /categories/{slug}
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }




    // -----------------------------------------------------
    // DEFAULT CATEGORY COLORS
    // -----------------------------------------------------

    public function colorClass(): string
    {
        return match ($this->color) {
            'orange' => 'bg-orange-500',
            'red' => 'bg-red-500',
            'amber' => 'bg-amber-500',
            'yellow' => 'bg-yellow-500',
            'lime' => 'bg-lime-500',
            'green' => 'bg-green-500',
            'emerald' => 'bg-emerald-500',
            'teal' => 'bg-teal-500',
            'cyan' => 'bg-cyan-500',
            'sky' => 'bg-sky-500',
            'blue' => 'bg-blue-500',
            'indigo' => 'bg-indigo-500',
            'violet' => 'bg-violet-500',
            'purple' => 'bg-purple-500',
            'fuchsia' => 'bg-fuchsia-500',
            'pink' => 'bg-pink-500',
            'rose' => 'bg-rose-500',
            default => 'bg-stone-500',
        };
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

    
    


    // -----------------------------------------------------
    // RELATIONSHIPS
    // -----------------------------------------------------


    // Articles

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Criminal Case
    public function criminalCase()
    {
        return $this->belongsTo(CriminalCase::class);
    }

    // Published articles
    public function publishedArticles()
    {
        return $this->hasMany(Article::class)->published();
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
    
    // Singular name
    public function getSingularNameAttribute(): string
    {
        return Str::singular($this->name);
    }



    
    
}


