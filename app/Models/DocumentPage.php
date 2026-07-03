<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class DocumentPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hex',
        'user_id',
        'document_id',
        'page_number',
        'image_path',
        'width',
        'height',
        'views',
        'created_at',
        'updated_at',
    ];

    protected static function booted()
    {
        // static::creating(function ($document) {
        //     if (empty($document->slug)) {
        //         $document->slug = Str::slug($document->name);
        //     }
        // });
    }


    /*
    |--------------------------------------------------------------------------
    | Route Binding (IMPORTANT)
    | This makes URLs use /documents-pages/{hex}
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'hex';
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

    // Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }




    
    
}


