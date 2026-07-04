<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    // SHOW

    public function show(CriminalCase $criminalCase, Document $document)
    {
        // Optional: ensure the document belongs to the case
        abort_unless($document->criminal_case_id === $criminalCase->id, 404);
        
        // Increment the views
        $document->increment('views');

        return view('documents.show', [
            'criminalCase' => $criminalCase,
            'document' => $document,
        ]);
    }
    
}
