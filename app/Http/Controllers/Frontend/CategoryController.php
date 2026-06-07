<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;


// -----------------------------------------------------
// CATEGORY CONTROLLER (FRONTEND)
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
    // SHOW
    // -----------------------------------------------------

    public function show(Category $category)
    {
        $articles = $category->articles()
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return view('categories.show', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }


}