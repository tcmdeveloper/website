<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $timelines = Timeline::query()
            ->published()
            ->latestFirst()
            // ->withCount('events')
            ->paginate(12);

        
        return view('timelines.index', [
            'timelines' => $timelines
        ]);
    }


    //

    
}
