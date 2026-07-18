<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Judge extends Model
{
    public const PER_PAGE = 12;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'court'
    ];


    /*
    |--------------------------------------------------------------------------
    | 
    |--------------------------------------------------------------------------
    */

    public function criminalCases()
    {
        return $this->hasMany(CriminalCase::class);
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
                    'alt_text' => 'Default Judge image',
                ]),
        );
    }


}
