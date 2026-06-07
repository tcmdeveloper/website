<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;


// -----------------------------------------------------
// CATEGORY CONTROLLER
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

    
}