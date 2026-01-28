<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class CitizenController extends Controller
{
    public function index()
    {
        $myApplications = auth()->user()->applications;
        return view('dashboards.citizen', compact('myApplications'));
    }
}
