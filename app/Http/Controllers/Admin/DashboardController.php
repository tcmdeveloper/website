<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\CriminalCase;
use App\Models\Document;
use App\Models\User;
use App\Models\Video;


// -----------------------------------------------------
// DASHBOARD CONTROLLER (ADMIN)
// -----------------------------------------------------

class DashboardController extends Controller
{

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        return view('dashboard.index', [
            'criminalCaseCount' => CriminalCase::count(),
            'categoryCount' => Category::count(),
            'articleCount' => Article::count(),
            'documentCount' => Document::count(),
            'videoCount' => Video::count(),
            'userCount' => User::count(),
            'publishedCount' => Article::where('is_published', true)->count(),
        ]);
    }

}
