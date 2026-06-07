<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use League\CommonMark\CommonMarkConverter;
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
        $converter = new CommonMarkConverter();

        $content = $article->content;

        if (blank($content)) {
            $html = '<p class="text-gray-400">No content available.</p>';
        } else {
            $html = $converter->convert($content);
        }

        return view('articles.show', [
            'article' => $article,
            'html' => $html,
        ]);
    }


}