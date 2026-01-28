<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificateApplication;
use App\Models\User;

class ApplicationManageController extends Controller
{
    /**
     * Display all applications
     */
    public function index()
    {
        $applications = CertificateApplication::with([
            'user:id,name,email',
            'certificateType:id,name,fee',
            'invoice',
            'approvedBy:id,name',
            'rejectedBy:id,name'
        ])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('super_admin.applications.index', compact('applications'));
    }

    /**
     * Show application details
     */
    public function show($id)
    {
        $application = CertificateApplication::with([
            'user',
            'certificateType',
            'approvedBy',
            'rejectedBy',
            'invoice'
        ])->findOrFail($id);
        
        // Get form data properly
        $formData = [];
        if ($application->form_data) {
            if (is_string($application->form_data)) {
                $formData = json_decode($application->form_data, true);
            } else {
                $formData = $application->form_data;
            }
        }
        
        // Get form fields properly
        $formFields = [];
        if ($application->fields) {
            if (is_string($application->fields)) {
                $formFields = json_decode($application->fields, true);
            } else {
                $formFields = $application->fields;
            }
        }
        
        // Get certificate form fields
        $certificateFormFields = [];
        if ($application->certificateType && $application->certificateType->form_fields) {
            if (is_string($application->certificateType->form_fields)) {
                $certificateFormFields = json_decode($application->certificateType->form_fields, true);
            } else {
                $certificateFormFields = $application->certificateType->form_fields;
            }
        }
        
        return view('super_admin.applications.show', compact(
            'application',
            'formData',
            'formFields',
            'certificateFormFields'
        ));
    }

    /**
     * Approve application
     */
    public function approve(Request $request, $id)
    {
        $application = CertificateApplication::findOrFail($id);
        
        // Generate certificate number
        $certificateNumber = 'CERT-' . date('Ymd') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
        
        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'certificate_number' => $certificateNumber,
        ]);
        
        return redirect()->route('super_admin.applications.index')
            ->with('success', 'Application #' . $application->id . ' has been approved.');
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
            ->with('success', 'Application #' . $application->id . ' has been rejected.');
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
            ->with('success', 'Payment status updated for application #' . $application->id);
    }

    /**
     * Search applications
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $applications = CertificateApplication::with(['user', 'certificateType'])
            ->where(function($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhere('payment_status', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('certificateType', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('super_admin.applications.index', compact('applications', 'search'));
    }

    /**
     * Update application
     */
    public function update(Request $request, $id)
    {
        $application = CertificateApplication::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'payment_status' => 'required|in:paid,unpaid',
            'remarks' => 'nullable|string|max:1000',
        ]);
        
        $updateData = [
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'remarks' => $request->remarks,
        ];
        
        // Handle approval
        if ($request->status === 'approved' && $application->status !== 'approved') {
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = auth()->id();
            $updateData['certificate_number'] = 'CERT-' . date('Ymd') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
        }
        
        // Handle rejection
        if ($request->status === 'rejected' && $application->status !== 'rejected') {
            $updateData['rejected_at'] = now();
            $updateData['rejected_by'] = auth()->id();
        }
        
        // Handle payment
        if ($request->payment_status === 'paid' && $application->payment_status !== 'paid') {
            $updateData['paid_at'] = now();
        }
        
        $application->update($updateData);
        
        return redirect()->route('super_admin.applications.show', $application->id)
            ->with('success', 'Application updated successfully.');
    }

    /**
     * Delete application
     */
    public function destroy($id)
    {
        $application = CertificateApplication::findOrFail($id);
        
        // Check if can be deleted (only pending applications)
        if ($application->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending applications can be deleted.');
        }
        
        $application->delete();
        
        return redirect()->route('super_admin.applications.index')
            ->with('success', 'Application deleted successfully.');
    }

    /**
     * Application statistics
     */
    public function statistics()
    {
        $total = CertificateApplication::count();
        $pending = CertificateApplication::where('status', 'pending')->count();
        $approved = CertificateApplication::where('status', 'approved')->count();
        $rejected = CertificateApplication::where('status', 'rejected')->count();
        $paid = CertificateApplication::where('payment_status', 'paid')->count();
        $unpaid = CertificateApplication::where('payment_status', 'unpaid')->count();
        
        $revenue = CertificateApplication::where('status', 'approved')->sum('fee');
        
        return view('super_admin.applications.statistics', compact(
            'total', 'pending', 'approved', 'rejected', 'paid', 'unpaid', 'revenue'
        ));
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'applications' => 'required|array',
            'applications.*' => 'exists:applications,id',
        ]);
        
        $action = $request->action;
        $applicationIds = $request->applications;
        
        switch ($action) {
            case 'approve':
                CertificateApplication::whereIn('id', $applicationIds)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'approved_by' => auth()->id(),
                    ]);
                $message = 'Selected applications have been approved.';
                break;
                
            case 'reject':
                CertificateApplication::whereIn('id', $applicationIds)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejected_by' => auth()->id(),
                    ]);
                $message = 'Selected applications have been rejected.';
                break;
                
            case 'delete':
                CertificateApplication::whereIn('id', $applicationIds)
                    ->where('status', 'pending')
                    ->delete();
                $message = 'Selected applications have been deleted.';
                break;
        }
        
        return redirect()->route('super_admin.applications.index')
            ->with('success', $message);
    }
}