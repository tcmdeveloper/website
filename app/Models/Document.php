<?php

namespace App\Models;

use App\Models\DocumentPage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'meta_title',
        'meta_description',
        'og_image',
        'views',
        'user_id',
        'category_id',
        'criminal_case_id',
        'published_at',
        'is_published',
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

    // Criminal Case
    public function criminalCase()
    {
        return $this->belongsTo(CriminalCase::class);
    }

    // Document Pages
    public function documentPages()
    {
        return $this->hasMany(DocumentPage::class);
    }




    
    
}


