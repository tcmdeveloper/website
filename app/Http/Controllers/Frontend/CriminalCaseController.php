<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use Illuminate\Http\Request;


// -----------------------------------------------------
// CRIMINAL CASE CONTROLLER (FRONT-END)
// -----------------------------------------------------

class CriminalCaseController extends Controller
{

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $criminalCases = CriminalCase::query()
            ->orderBy('name')
            ->withCount('documents')
            ->paginate(12);
        
        return view('criminal-cases.index', [
            'criminalCases' => $criminalCases
        ]);
    }


    // -----------------------------------------------------
    // SHOW
    // -----------------------------------------------------

    public function show(CriminalCase $criminalCase)
    {   
        $criminalCase->load('documents.coverPage');
        $criminalCase->increment('views');
        

        return view('criminal-cases.show', [
            'criminalCase' => $criminalCase,
        ]);
    }
    
}
