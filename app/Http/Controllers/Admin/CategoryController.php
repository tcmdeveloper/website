<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;


// -----------------------------------------------------
// CATEGORY CONTROLLER (ADMIN)
// -----------------------------------------------------

class CategoryController extends Controller
{

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $categories = Category::query()
            ->whereHas('publishedArticles')
            ->orderBy('name')
            ->paginate(10);
        
        return view('categories.index', compact('categories'));
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email:rfc,dns', 'max:150'],
            'subject'   => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Category::create($validated);

        return back()->with('success', 'New category added!');
    }
    
}