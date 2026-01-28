<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificateApplication;
use App\Models\User;

class ApplicationsController extends Controller
{
    /**
     * Display all applications
     */
    public function index()
    {
        // Eager load all relationships
        $applications = CertificateApplication::with([
            'user:id,name,email,phone',
            'certificateType:id,name,fee,template',
            'approvedBy:id,name',
            'rejectedBy:id,name'
        ])
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Debug information
        if (config('app.debug')) {
            echo "<pre>";
            echo "=== DEBUG: ApplicationsController ===\n";
            echo "Total Applications: " . $applications->count() . "\n\n";
            
            if ($applications->count() > 0) {
                echo "First 3 Applications:\n";
                foreach ($applications->take(3) as $app) {
                    echo "ID: " . $app->id . "\n";
                    echo "App No: " . $app->application_no . "\n";
                    echo "User: " . ($app->user ? $app->user->name : 'N/A') . "\n";
                    echo "Cert Type: " . ($app->certificateType ? $app->certificateType->name : 'N/A') . "\n";
                    echo "Status: " . $app->status . "\n";
                    echo "---\n";
                }
            }
            
            // Remove exit() after testing
            // exit();
        }
        
        return view('super_admin.applications.index', compact('applications'));
    }

    /**
     * Show single application
     */
    public function show($id)
    {
        $application = CertificateApplication::with([
            'user',
            'certificateType',
            'approvedBy',
            'rejectedBy'
        ])->findOrFail($id);
        
        return view('super_admin.applications.show', compact('application'));
    }

    /**
     * Approve application
     */
    public function approve(Request $request, $id)
    {
        $application = CertificateApplication::findOrFail($id);
        
        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'certificate_number' => 'CERT-' . date('Ymd') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT),
        ]);
        
        return redirect()->route('super_admin.applications.index')
            ->with('success', 'Application #' . $application->application_no . ' has been approved.');
    }

    /**
     * Reject application
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);
        
        $application = CertificateApplication::findOrFail($id);
        
        $application->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        return redirect()->route('super_admin.applications.index')
            ->with('success', 'Application #' . $application->application_no . ' has been rejected.');
    }

    /**
     * Search applications
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $applications = CertificateApplication::with(['user', 'certificateType'])
            ->where(function($query) use ($search) {
                $query->where('application_no', 'LIKE', "%{$search}%")
                    ->orWhere('certificate_number', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%")
                          ->orWhere('phone', 'LIKE', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('super_admin.applications.index', compact('applications', 'search'));
    }

    /**
     * Update payment status
     */
    public function updatePayment(Request $request, $id)
    {
        $application = CertificateApplication::findOrFail($id);
        
        $application->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
        
        return redirect()->route('super_admin.applications.index')
            ->with('success', 'Payment status updated for application #' . $application->application_no);
    }
}