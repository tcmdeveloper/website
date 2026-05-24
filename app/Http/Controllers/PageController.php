<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{

    // Contructor class

    public function __construct(
        
    ){}


    // Show homepage

    public function home()
    {
        return view('pages.home', [
            'title' => 'This is my title',
            'subtitle' => 'This is my subtitle.'
        ]);


    }


}
    