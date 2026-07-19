<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class VideoPresenterController extends Controller
{
    // -----------------------------------------------------
    // PRESENT
    // -----------------------------------------------------

    function present()
    {
        return view('video-presenter.present');
    }
    

}
