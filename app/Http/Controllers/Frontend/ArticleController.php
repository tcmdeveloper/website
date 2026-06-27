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
        $articles = Article::published()->orderBy('published_at', 'desc')->paginate(10);

        return view('articles.index', compact('articles'));

    }


    // -----------------------------------------------------
    // SHOW
    // -----------------------------------------------------

    public function show(Article $article)
    {
        $article->increment('views');

        return view('articles.show', [
            'article' => $article,
        ]);
    }


}