<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocketEntry extends Model
{
    
    protected $fillable = [
        'hex',
        'criminal_case_id',
        'sequence_number',
        'docket_code',
        'filed_at',
        'title',
        'has_document',
        'input_document_id',
        'document_title',
        'page_count',
        'raw_data',
        'pdf_path',
        'encoded_document_path',
    ];


    protected $casts = [
        'raw_data' => 'array',
        'filed_at' => 'date',
    ];


    public function criminalCase()
    {
        return $this->belongsTo(
            CriminalCase::class,
            'criminal_case_id'
        );
    }


    public function document()
    {
        return $this->hasOne(Document::class);
    }
}
