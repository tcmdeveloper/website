<?php

namespace App\Models;

use App\Models\DocumentPage;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'docket_entry_id',
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



    // -----------------------------------------------------
    // MODEL EVENTS
    // Automatically generate a slug from the document name
    // when a new record is created and no slug is provided.
    // -----------------------------------------------------

    protected static function booted()
    {
        static::creating(function ($document) {
            if (empty($document->slug)) {
                $document->slug = Str::slug($document->name);
            }
        });
    }


    /*
    |-------------------------------------------------------
    | This makes URLs use /documents/{slug}
    |-------------------------------------------------------
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

    // CRIMINAL CASE

    public function criminalCase()
    {
        return $this->belongsTo(CriminalCase::class);
    }


    // DOCKET ENTRY

    public function docketEntry()
    {
        return $this->belongsTo(DocketEntry::class);
    }

    
    // DOCUMENT PAGES

    public function documentPages()
    {
        return $this->hasMany(DocumentPage::class);
    }

    
    // COVER PAGE

    public function coverPage()
    {
        return $this->hasOne(DocumentPage::class)->oldestOfMany('page_number');
    }


    // AUTHOR

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // -----------------------------------------------------
    // RELATIONSHIPS
    // -----------------------------------------------------

    // FORMATTED

    protected function formattedViews(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->views),
        );
    }


}


