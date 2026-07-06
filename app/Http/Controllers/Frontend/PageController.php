<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;


// -----------------------------------------------------
// PAGE CONTROLLER (FRONTEND)
// -----------------------------------------------------

class PageController extends Controller
{

    // -----------------------------------------------------
    // CONSTRUCTOR
    // -----------------------------------------------------

    public function __construct(
        
    ){}


    public function showCategory()
    {
        return [];
    }


    // -----------------------------------------------------
    // HOMEPAGE
    // -----------------------------------------------------

    public function home()
    {
        $articles = Article::published()
            ->orderBy('created_at', 'desc')
            // ->paginate(config('pagination.per_page'))
            ->paginate(Article::PER_PAGE);
            

        return view('pages.home', compact('articles'));
    }


    // -----------------------------------------------------
    // ABOUT
    // -----------------------------------------------------

    public function about()
    {
        return view('pages.about');
    }


    // -----------------------------------------------------
    // CONTACT
    // -----------------------------------------------------

    public function contact()
    {
        return view('pages.contact');
    }


    // -----------------------------------------------------
    // TERMS
    // -----------------------------------------------------

    public function terms()
    {
        return view('pages.terms');
    }


    // -----------------------------------------------------
    // PRIVACY
    // -----------------------------------------------------

    public function privacy()
    {
        return view('pages.privacy');
    }


}
    