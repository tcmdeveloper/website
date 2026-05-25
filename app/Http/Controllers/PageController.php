<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{

    // CONSTRUCTOR

    public function __construct(
        
    ){}


    // HOMEPAGE

    public function home()
    {
        return view('pages.home');
    }


    // ABOUT

    public function about()
    {
        return view('pages.about');
    }


    // CONTACT

    public function contact()
    {
        return view('pages.contact');
    }


    // TERMS

    public function terms()
    {
        return view('pages.terms');
    }


    // PRIVACY

    public function privacy()
    {
        return view('pages.privacy');
    }


}
    