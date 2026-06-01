<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Models\Trascription;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
