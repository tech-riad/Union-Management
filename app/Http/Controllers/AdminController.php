<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class AdminController extends Controller
{
    public function index()
    {
        $pendingApplications = Application::where('status','Pending')->get();
        return view('dashboards.admin', compact('pendingApplications'));
    }
}
