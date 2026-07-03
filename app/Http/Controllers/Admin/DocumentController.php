<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDocument;
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
        return view('documents.create');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:102400'], // 100 MB
            'name' => ['required', 'string', 'max:100', 'unique:documents,name'],
            'description' => ['required', 'string', 'max:300'],
        ]);
        

        $validated['hex'] = $generator->uniqueHexId();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['pdf_path'] = $request->file('pdf')->store('documents', 'public');
        $validated['user_id'] = auth()->id();

        
        // Ensure the generated slug is unique
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', 'unique:documents,slug'],
        ])->validate();


        $document = Document::create([
            'hex' => $validated['hex'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'pdf_path' => $validated['pdf_path'],
            'user_id' => $validated['user_id'],
        ]);

        try{
            ProcessDocument::dispatchSync($document->id);
        } 
        catch (\Throwable $e) {

            dd(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
        }

        return redirect( route('admin.documents.index') )->with('success', 'New document added.');

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