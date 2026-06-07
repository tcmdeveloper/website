<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;


// -----------------------------------------------------
// ARTICLE CONTROLLER (FRONTEND)
// -----------------------------------------------------

class ArticleController extends Controller
{   

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        return view('articles.show');
    }


    // -----------------------------------------------------
    // SHOW
    // -----------------------------------------------------

    public function show(Article $article)
    {
        return view('articles.show', [
            'article' => $article,
        ]);
    }


}