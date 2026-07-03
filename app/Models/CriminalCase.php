<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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


    /*
    |--------------------------------------------------------------------------
    | Relationships
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

    // Images
    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }
}
