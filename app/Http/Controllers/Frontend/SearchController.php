<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q'));

        $articles = Article::query()
            ->published()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                });
            })
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('search.index', [
            'articles' => $articles,
            'query' => $q,
            'meta' => [
                'title' => $q
                    ? "Search: {$q} | True Crime Metrix"
                    : "Search | True Crime Metrix",
                'description' => $q
                    ? "Search results for '{$q}'."
                    : "Search articles.",
            ],
        ]);
    }
}
