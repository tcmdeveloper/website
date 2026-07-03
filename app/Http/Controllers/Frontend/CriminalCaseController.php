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
        $criminalCases = CriminalCase::orderBy('name')->paginate(10);
        
        return view('categories.index', [
            'criminalCases' => $criminalCases
        ]);
    }
    
}
