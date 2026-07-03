<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


// -----------------------------------------------------
// CRIMINAL CASE CONTROLLER (ADMIN)
// -----------------------------------------------------

class CriminalCaseController extends Controller
{

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $criminalCases = CriminalCase::orderBy('name')->paginate(10);
        
        return view('criminal-cases.admin-index', [
            'criminalCases' => $criminalCases
        ]);
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {
        return view('criminal-cases.create');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['required', 'string', 'max:300'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = auth()->id();
        $validated['hex'] = $generator->uniqueHexId();

        // Ensure the generated slug is unique
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', 'unique:criminal_cases,slug'],
        ])->validate();

        CriminalCase::create($validated);

        return redirect( route('admin.criminal-cases.index') )->with('success', 'New case added.');

    }
    
}
