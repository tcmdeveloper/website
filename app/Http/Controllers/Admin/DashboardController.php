<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;


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
            'articleCount' => Article::count(),
            'categoryCount' => Category::count(),
            'userCount' => User::count(),
            'publishedCount' => Article::where('is_published', true)->count(),
        ]);
    }

}
