<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


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
        return view('admin.dashboard');
    }

}
