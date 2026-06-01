<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show($hex)
    {
        $article = Article::where('hex', $hex)->firstOrFail();
        return view('articles.show', compact('article'));
    }
}
