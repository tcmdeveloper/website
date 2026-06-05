<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use League\CommonMark\CommonMarkConverter;
use App\Models\Article;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        $converter = new CommonMarkConverter();

        $contentHtml = $converter->convert($article->content);

        return view('articles.show', [
            'article' => $article,
            'contentHtml' => $contentHtml,
        ]);
    }
}