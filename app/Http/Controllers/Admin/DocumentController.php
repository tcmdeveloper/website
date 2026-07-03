<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDocument;
use App\Models\CriminalCase;
use App\Models\Document;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


// -----------------------------------------------------
// Document CONTROLLER (ADMIN)
// -----------------------------------------------------

class DocumentController extends Controller
{

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $documents = Document::orderBy('name')->paginate(10);
        
        return view('documents.admin-index', [
            'documents' => $documents
        ]);
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {
        return view('documents.create', [
            'criminalCases' => CriminalCase::orderBy('name')->pluck('name', 'id')
        ]);
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'criminal_case_id' => ['required', 'integer', 'exists:criminal_cases,id'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:102400'], // 100 MB
            'name' => ['required', 'string', 'max:100', 'unique:documents,name'],
            'description' => ['required', 'string', 'max:300'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        
        // Assign auto vars
        $validated['hex'] = $generator->uniqueHexId();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['pdf_path'] = $request->file('pdf')->store('documents', 'public');
        $validated['user_id'] = auth()->id();

        
        // Ensure the generated slug is unique
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', 'unique:documents,slug'],
        ])->validate();


        // Insert document into the database
        $document = Document::create([
            'hex' => $validated['hex'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'pdf_path' => $validated['pdf_path'],
            'user_id' => $validated['user_id'],
            'criminal_case_id' => $validated['criminal_case_id'],
            'is_published' => $validated['is_published'],
            'published_at' => $validated['is_published'] ? now() : null,
        ]);

        
        // Start processing the document
        ProcessDocument::dispatch($document->id);
        
        return redirect()
            ->route('admin.documents.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Document processing started. Check back in a few minutes.',
            ]);

    }


    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Document $document)
    {
        $document->articles()->update([
            'document_id' => null
        ]);

        $document->delete();
        
        return redirect()
            ->route('admin.documents.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Document deleted.',
            ]);
    }

    
}