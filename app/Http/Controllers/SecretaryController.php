<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class SecretaryController extends Controller
{
    // List all pending applications
    public function pendingApplications()
    {
        $applications = Application::where('status', 'Pending')->get();
        return view('dashboards.secretary.applications.pending', compact('applications'));
    }

    // Approve application
    public function approve(Application $application)
    {
        $application->update(['status' => 'Approved']);
        return back()->with('success', 'Application approved successfully!');
    }

    // Reject application
    public function reject(Application $application)
    {
        $application->update(['status' => 'Rejected']);
        return back()->with('success', 'Application rejected successfully!');
    }
}
