<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'hex',
        'name',
        'slug',
        'description',
        'pdf_path',
        'pages',
        'filesize',
        'views',
        'user_id',
        'category_id',
        'criminal_case_id',
        'published_at',
        'is_published',
    ];

    protected static function booted()
    {
        static::creating(function ($document) {
            if (empty($document->slug)) {
                $document->slug = Str::slug($document->name);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Route Binding (IMPORTANT)
    | This makes URLs use /documents/{slug}
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }



//This is from categories
    // -----------------------------------------------------
    // SCOPES
    // -----------------------------------------------------

    // public function scopePublished($query)
    // {
    //     return $query
    //         ->where('is_published', true)
    //         ->whereNotNull('published_at')
    //         ->where('published_at', '<=', now());
    // }

    
    


    // -----------------------------------------------------
    // RELATIONSHIPS
    // -----------------------------------------------------

    // Criminal Case
    public function criminalCase()
    {
        return $this->belongsTo(CriminalCase::class);
    }

    // Pages
    public function pages()
    {
        return $this->hasMany(DocumentPage::class);
    }




    
    
}


