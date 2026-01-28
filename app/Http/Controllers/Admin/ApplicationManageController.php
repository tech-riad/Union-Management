<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ActivityLog; // এই লাইন যোগ করুন
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplicationManageController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        Log::info('Admin Application Index accessed', [
            'user_id' => auth()->id(),
            'search' => $request->search,
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);
        
        // Log this activity in ActivityLog table
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'user_role' => auth()->user()->role,
                'action' => 'VIEW',
                'module' => 'APPLICATION',
                'description' => "Admin viewed applications list",
                'old_data' => null,
                'new_data' => json_encode([
                    'search' => $request->search,
                    'status' => $request->status,
                    'payment_status' => $request->payment_status,
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
        
        // Eager load all necessary relationships with invoice
        // IMPORTANT: 'invoice' এর সাথে 'invoice.payment_status' ব্যবহার করুন, 'invoice.status' নয়
        $query = Application::with([
            'user:id,name,email,mobile',
            'certificateType:id,name,fee',
            'approvedBy:id,name',
            'rejectedBy:id,name',
            'invoice:id,application_id,payment_status,amount,paid_at,invoice_no' // payment_status ব্যবহার করুন
        ]);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('mobile', 'like', "%{$search}%");
                  })
                  ->orWhereHas('certificateType', function($certQuery) use ($search) {
                      $certQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('invoice', function($invoiceQuery) use ($search) {
                      $invoiceQuery->where('invoice_no', 'like', "%{$search}%")
                                  ->orWhere('transaction_id', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by user if specified
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $applications = $query->latest()->paginate(20);
        
        // Log applications count for debugging
        Log::info('Applications found', [
            'total' => $applications->total(),
            'current_page' => $applications->currentPage(),
            'pending_count' => $applications->where('status', 'pending')->count(),
            'paid_count' => $applications->where('payment_status', 'paid')->count()
        ]);
        
        // Get statistics
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];
        
        return view('admin.applications.index', compact('applications', 'stats'));
    }
    
    /**
     * Show single application with debug info
     */
    public function show($id)
    {
        Log::info('Admin Application Show accessed', [
            'application_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $application = Application::with([
            'user',
            'certificateType',
            'approvedBy',
            'rejectedBy',
            'invoice'
        ])->findOrFail($id);
        
        // Log this activity in ActivityLog table
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'user_role' => auth()->user()->role,
                'action' => 'VIEW',
                'module' => 'APPLICATION',
                'description' => "Admin viewed application #{$application->id}",
                'old_data' => null,
                'new_data' => json_encode([
                    'application_id' => $application->id,
                    'status' => $application->status,
                    'user_name' => $application->user->name ?? 'N/A'
                ]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
        
        // Parse form data
        $formData = [];
        if ($application->form_data) {
            if (is_string($application->form_data)) {
                $formData = json_decode($application->form_data, true);
            } else {
                $formData = $application->form_data;
            }
        }
        
        // Get application statistics for sidebar
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];
        
        // Check if can be approved
        $canApprove = false;
        $approvalCheckDetails = [];
        
        if ($application->status === 'pending') {
            // Check payment status
            $approvalCheckDetails['payment_status'] = $application->payment_status;
            $approvalCheckDetails['has_invoice'] = $application->invoice ? 'Yes' : 'No';
            
            if ($application->invoice) {
                // IMPORTANT: 'payment_status' ব্যবহার করুন, 'status' নয়
                $approvalCheckDetails['invoice_payment_status'] = $application->invoice->payment_status;
                $approvalCheckDetails['invoice_paid_at'] = $application->invoice->paid_at;
                $approvalCheckDetails['invoice_no'] = $application->invoice->invoice_no;
            }
            
            // Determine if can approve - TEMPORARY FIX: সরাসরি check করুন
            $canApprove = $this->canApproveApplicationSimple($application);
            $approvalCheckDetails['can_approve'] = $canApprove ? 'Yes' : 'No';
            $approvalCheckDetails['approval_reason'] = $canApprove ? 'Payment verified' : $this->getApprovalErrorMessageSimple($application);
        }
        
        // Log approval check details
        Log::info('Application show approval check:', array_merge(
            ['application_id' => $application->id],
            $approvalCheckDetails
        ));
        
        return view('admin.applications.show', compact(
            'application', 
            'formData', 
            'stats',
            'canApprove',
            'approvalCheckDetails'
        ));
    }
    
    /**
     * TEMPORARY FIX: Simple helper function to check if application can be approved
     * This bypasses complex checks and just looks at application payment_status
     */
    private function canApproveApplicationSimple(Application $application): bool
    {
        Log::info('Simple approval check for application:', [
            'application_id' => $application->id,
            'application_status' => $application->status,
            'application_payment_status' => $application->payment_status,
            'has_invoice' => $application->invoice ? 'Yes' : 'No',
        ]);
        
        // First check application status
        if ($application->status !== 'pending') {
            Log::warning('Application is not pending', [
                'application_id' => $application->id,
                'current_status' => $application->status
            ]);
            return false;
        }
        
        // SIMPLE CHECK: Just check application payment_status
        if ($application->payment_status === 'paid') {
            Log::info('✓ Application payment_status is paid - Can approve', [
                'application_id' => $application->id,
                'payment_status' => $application->payment_status
            ]);
            return true;
        }
        
        // Also check invoice if exists
        if ($application->invoice) {
            Log::info('Invoice found for application:', [
                'application_id' => $application->id,
                'invoice_id' => $application->invoice->id,
                'invoice_payment_status' => $application->invoice->payment_status,
                'invoice_paid_at' => $application->invoice->paid_at
            ]);
            
            // Check invoice payment_status
            if ($application->invoice->payment_status === 'paid') {
                Log::info('✓ Invoice payment_status is paid - Can approve', [
                    'application_id' => $application->id,
                    'invoice_payment_status' => $application->invoice->payment_status
                ]);
                return true;
            }
            
            // Also check if invoice has status field (for backward compatibility)
            // Note: This might cause error if 'status' column doesn't exist
            try {
                if (isset($application->invoice->status) && $application->invoice->status === 'paid') {
                    Log::info('✓ Invoice status field is paid - Can approve', [
                        'application_id' => $application->id,
                        'invoice_status' => $application->invoice->status
                    ]);
                    return true;
                }
            } catch (\Exception $e) {
                Log::warning('Error checking invoice status field:', [
                    'application_id' => $application->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::warning('✗ Cannot approve application - Payment not verified', [
            'application_id' => $application->id,
            'application_payment_status' => $application->payment_status,
            'invoice_exists' => $application->invoice ? 'Yes' : 'No',
            'invoice_payment_status' => $application->invoice ? $application->invoice->payment_status : 'N/A'
        ]);
        
        return false;
    }
    
    /**
     * TEMPORARY FIX: Simple helper function to get approval error message
     */
    private function getApprovalErrorMessageSimple(Application $application): string
    {
        if ($application->payment_status !== 'paid') {
            return "Application payment status is '{$application->payment_status}'. Required: 'paid'";
        }
        
        if ($application->invoice && $application->invoice->payment_status !== 'paid') {
            return "Invoice payment status is '{$application->invoice->payment_status}'. Required: 'paid'";
        }
        
        return "Payment verification failed";
    }
    
    /**
     * Approve application - SIMPLIFIED VERSION with temporary fix
     */
    public function approve($id, Request $request)
    {
        Log::info('Admin Approve method called', [
            'application_id' => $id,
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);
        
        $request->validate([
            'certificate_number' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:1000',
        ]);
        
        $application = Application::with(['user', 'invoice'])->findOrFail($id);
        
        Log::info('Application found for approval:', [
            'id' => $application->id,
            'status' => $application->status,
            'payment_status' => $application->payment_status,
            'has_invoice' => $application->invoice ? 'Yes' : 'No',
            'invoice_payment_status' => $application->invoice ? $application->invoice->payment_status : 'No invoice',
            'invoice_paid_at' => $application->invoice && $application->invoice->paid_at ? $application->invoice->paid_at->format('Y-m-d H:i:s') : 'No paid_at'
        ]);
        
        // Check if already approved
        if ($application->status === 'approved') {
            Log::warning('Application already approved', ['application_id' => $application->id]);
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Application is already approved.');
        }
        
        // TEMPORARY FIX: Use simple check
        if (!$this->canApproveApplicationSimple($application)) {
            $errorMessage = $this->getApprovalErrorMessageSimple($application);
            Log::error('Cannot approve application. ' . $errorMessage, ['application_id' => $application->id]);
            
            // Show detailed error message
            $detailedError = "Payment verification failed. ";
            $detailedError .= "Application payment status: " . ($application->payment_status ?? 'unpaid') . ". ";
            
            if ($application->invoice) {
                $detailedError .= "Invoice payment status: " . ($application->invoice->payment_status ?? 'unpaid') . ". ";
            }
            
            return redirect()->route('admin.applications.show', $id)
                ->with('error', $detailedError);
        }
        
        Log::info('✓ Payment check passed. Proceeding with approval...', ['application_id' => $application->id]);
        
        // Generate certificate number if not provided
        $certificateNumber = $request->certificate_number ?? 
            'CERT-' . date('Ymd') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
        
        try {
            $updateData = [
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'certificate_number' => $certificateNumber,
                'remarks' => $request->remarks,
                'rejected_at' => null,
                'rejected_by' => null,
                'rejection_reason' => null,
            ];
            
            $application->update($updateData);
            
            Log::info('✓ Application approved successfully:', [
                'id' => $application->id,
                'certificate_number' => $certificateNumber,
                'approved_by' => auth()->id(),
                'approved_at' => now()->format('Y-m-d H:i:s')
            ]);
            
            // Log this activity in ActivityLog table
            try {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'APPROVE',
                    'module' => 'APPLICATION',
                    'description' => "Application #{$application->id} approved by admin",
                    'old_data' => json_encode([
                        'status' => 'pending',
                        'approved_at' => null,
                        'approved_by' => null
                    ]),
                    'new_data' => json_encode([
                        'application_id' => $application->id,
                        'status' => 'approved',
                        'certificate_number' => $certificateNumber,
                        'approved_by' => auth()->user()->name,
                        'approved_at' => now()->toDateTimeString(),
                        'remarks' => $request->remarks
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log approval activity: ' . $e->getMessage());
            }
            
            // Send notification to user (if needed)
            // if ($application->user) {
            //     // Send email or notification here
            // }
            
            return redirect()->route('admin.applications.show', $id)
                ->with('success', 'Application #' . $application->id . ' has been approved successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error approving application:', [
                'id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Error approving application: ' . $e->getMessage());
        }
    }
    
    /**
     * Emergency approve - Bypass payment check (Only for super admin)
     */
    public function emergencyApprove($id, Request $request)
    {
        // Only super admin can use this
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Unauthorized access.');
        }
        
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
        
        $application = Application::with('invoice')->findOrFail($id);
        
        Log::warning('⚠️ EMERGENCY APPROVE USED', [
            'application_id' => $application->id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'application_status' => $application->status,
            'payment_status' => $application->payment_status
        ]);
        
        // Check if already approved
        if ($application->status === 'approved') {
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Application is already approved.');
        }
        
        // Generate certificate number
        $certificateNumber = 'CERT-EMG-' . date('Ymd') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
        
        try {
            $application->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'certificate_number' => $certificateNumber,
                'remarks' => 'EMERGENCY APPROVAL: ' . $request->reason,
                'payment_status' => 'paid', // Force set as paid
            ]);
            
            // If invoice exists, mark it as paid
            if ($application->invoice) {
                $application->invoice->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'remarks' => 'Marked as paid via emergency approval'
                ]);
            }
            
            Log::info('✓ Emergency approval successful:', [
                'application_id' => $application->id,
                'certificate_number' => $certificateNumber
            ]);
            
            // Log this activity in ActivityLog table
            try {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'EMERGENCY_APPROVE',
                    'module' => 'APPLICATION',
                    'description' => "Application #{$application->id} emergency approved (payment check bypassed)",
                    'old_data' => json_encode([
                        'status' => $application->getOriginal('status'),
                        'payment_status' => $application->getOriginal('payment_status')
                    ]),
                    'new_data' => json_encode([
                        'application_id' => $application->id,
                        'status' => 'approved',
                        'certificate_number' => $certificateNumber,
                        'payment_status' => 'paid',
                        'approved_by' => auth()->user()->name,
                        'reason' => $request->reason,
                        'emergency_approval' => true
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log emergency approval activity: ' . $e->getMessage());
            }
            
            return redirect()->route('admin.applications.show', $id)
                ->with('success', 'Application #' . $application->id . ' has been EMERGENCY approved (payment check bypassed).');
                
        } catch (\Exception $e) {
            Log::error('Error in emergency approval:', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject application - Payment status check na kore reject kora jabe
     */
    public function reject($id, Request $request)
    {
        Log::info('Admin Reject method called', [
            'application_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
        ]);
        
        $application = Application::with('user')->findOrFail($id);
        
        // Check if already rejected
        if ($application->status === 'rejected') {
            Log::warning('Application already rejected', ['application_id' => $application->id]);
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Application is already rejected.');
        }
        
        try {
            $oldStatus = $application->status;
            
            $application->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->rejection_reason,
                'remarks' => $request->remarks,
                'approved_at' => null,
                'approved_by' => null,
                'certificate_number' => null,
            ]);
            
            Log::info('Application rejected successfully:', [
                'id' => $application->id,
                'rejected_by' => auth()->id(),
                'rejected_at' => now()->format('Y-m-d H:i:s')
            ]);
            
            // Log this activity in ActivityLog table
            try {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'REJECT',
                    'module' => 'APPLICATION',
                    'description' => "Application #{$application->id} rejected by admin",
                    'old_data' => json_encode([
                        'status' => $oldStatus,
                        'rejected_at' => null,
                        'rejected_by' => null
                    ]),
                    'new_data' => json_encode([
                        'application_id' => $application->id,
                        'status' => 'rejected',
                        'rejected_by' => auth()->user()->name,
                        'rejection_reason' => $request->rejection_reason,
                        'rejected_at' => now()->toDateTimeString(),
                        'remarks' => $request->remarks
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log rejection activity: ' . $e->getMessage());
            }
            
            return redirect()->route('admin.applications.show', $id)
                ->with('success', 'Application #' . $application->id . ' has been rejected.');
                
        } catch (\Exception $e) {
            Log::error('Error rejecting application:', [
                'id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Error rejecting application: ' . $e->getMessage());
        }
    }
    
    /**
     * Update application status
     */
    public function updateStatus($id, Request $request)
    {
        Log::info('Update Status method called', [
            'application_id' => $id,
            'status' => $request->status,
            'user_id' => auth()->id()
        ]);
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:1000',
            'certificate_number' => 'required_if:status,approved|nullable|string|max:100',
        ]);
        
        $application = Application::with('invoice')->findOrFail($id);
        
        // Store old data for logging
        $oldData = $application->toArray();
        
        // Check payment status if trying to approve
        if ($request->status === 'approved') {
            if (!$this->canApproveApplicationSimple($application)) {
                $errorMessage = $this->getApprovalErrorMessageSimple($application);
                Log::error('Cannot update status to approved. ' . $errorMessage, ['application_id' => $application->id]);
                
                return redirect()->route('admin.applications.show', $id)
                    ->with('error', 'Cannot approve application. ' . $errorMessage);
            }
        }
        
        $updateData = [
            'status' => $request->status,
            'remarks' => $request->remarks,
        ];
        
        if ($request->status === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = auth()->id();
            $updateData['certificate_number'] = $request->certificate_number ?? 
                'CERT-' . date('Ymd') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
            $updateData['rejected_at'] = null;
            $updateData['rejected_by'] = null;
            $updateData['rejection_reason'] = null;
        }
        
        if ($request->status === 'rejected') {
            $updateData['rejected_at'] = now();
            $updateData['rejected_by'] = auth()->id();
            $updateData['rejection_reason'] = $request->rejection_reason;
            $updateData['approved_at'] = null;
            $updateData['approved_by'] = null;
            $updateData['certificate_number'] = null;
        }
        
        if ($request->status === 'pending') {
            $updateData['approved_at'] = null;
            $updateData['approved_by'] = null;
            $updateData['rejected_at'] = null;
            $updateData['rejected_by'] = null;
            $updateData['rejection_reason'] = null;
        }
        
        try {
            $application->update($updateData);
            
            Log::info('Application status updated successfully:', [
                'id' => $application->id,
                'new_status' => $request->status,
                'updated_by' => auth()->id()
            ]);
            
            // Log this activity in ActivityLog table
            try {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'UPDATE',
                    'module' => 'APPLICATION',
                    'description' => "Application #{$application->id} status updated to '{$request->status}'",
                    'old_data' => json_encode($oldData),
                    'new_data' => json_encode($application->fresh()->toArray()),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log status update activity: ' . $e->getMessage());
            }
            
            return redirect()->route('admin.applications.show', $id)
                ->with('success', 'Application status updated successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error updating application status:', [
                'id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
    
    /**
     * Bulk actions - Only approve if paid
     */
    public function bulkAction(Request $request)
    {
        Log::info('Bulk Action called', [
            'action' => $request->action,
            'application_count' => count($request->application_ids ?? []),
            'user_id' => auth()->id()
        ]);
        
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
        ]);
        
        $action = $request->action;
        $applicationIds = $request->application_ids;
        
        switch ($action) {
            case 'approve':
                // Only approve applications that have paid invoices
                $applications = Application::whereIn('id', $applicationIds)
                    ->where('status', '!=', 'approved')
                    ->with('invoice')
                    ->get();
                
                $count = 0;
                $failedCount = 0;
                foreach ($applications as $application) {
                    // Check payment status using simple method
                    if ($this->canApproveApplicationSimple($application)) {
                        try {
                            $oldStatus = $application->status;
                            
                            $application->update([
                                'status' => 'approved',
                                'approved_at' => now(),
                                'approved_by' => auth()->id(),
                                'certificate_number' => 'CERT-' . date('Ymd') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT),
                            ]);
                            
                            $count++;
                            
                            // Log each approval in ActivityLog table
                            try {
                                ActivityLog::create([
                                    'user_id' => auth()->id(),
                                    'user_name' => auth()->user()->name,
                                    'user_role' => auth()->user()->role,
                                    'action' => 'BULK_APPROVE',
                                    'module' => 'APPLICATION',
                                    'description' => "Application #{$application->id} approved in bulk action",
                                    'old_data' => json_encode(['status' => $oldStatus]),
                                    'new_data' => json_encode([
                                        'application_id' => $application->id,
                                        'status' => 'approved',
                                        'certificate_number' => $application->certificate_number,
                                        'approved_by' => auth()->user()->name,
                                        'approved_at' => now()->toDateTimeString()
                                    ]),
                                    'ip_address' => $request->ip(),
                                    'user_agent' => $request->userAgent(),
                                    'url' => $request->fullUrl(),
                                    'method' => $request->method()
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to log bulk approval activity: ' . $e->getMessage());
                            }
                            
                            Log::info('Bulk approve successful:', ['application_id' => $application->id]);
                        } catch (\Exception $e) {
                            $failedCount++;
                            Log::error('Bulk approve failed:', [
                                'application_id' => $application->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    } else {
                        $failedCount++;
                        Log::warning('Bulk approve skipped - payment not verified:', [
                            'application_id' => $application->id,
                            'payment_status' => $application->payment_status,
                            'invoice_payment_status' => $application->invoice ? $application->invoice->payment_status : 'No invoice'
                        ]);
                    }
                }
                $message = $count . ' applications have been approved. ' . ($failedCount > 0 ? $failedCount . ' applications failed.' : '');
                break;
                
            case 'reject':
                // Reject any application regardless of payment status
                $count = Application::whereIn('id', $applicationIds)
                    ->where('status', '!=', 'rejected')
                    ->update([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejected_by' => auth()->id(),
                        'rejection_reason' => 'Bulk rejection',
                    ]);
                $message = $count . ' applications have been rejected.';
                Log::info('Bulk reject completed:', ['count' => $count]);
                
                // Log bulk rejection activity
                try {
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'user_name' => auth()->user()->name,
                        'user_role' => auth()->user()->role,
                        'action' => 'BULK_REJECT',
                        'module' => 'APPLICATION',
                        'description' => "Bulk rejected {$count} applications",
                        'old_data' => null,
                        'new_data' => json_encode([
                            'action' => 'bulk_reject',
                            'count' => $count,
                            'application_ids' => $applicationIds
                        ]),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'url' => $request->fullUrl(),
                        'method' => $request->method()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to log bulk rejection activity: ' . $e->getMessage());
                }
                break;
                
            case 'delete':
                // Only delete pending applications
                $count = Application::whereIn('id', $applicationIds)
                    ->where('status', 'pending')
                    ->delete();
                $message = $count . ' applications have been deleted.';
                Log::info('Bulk delete completed:', ['count' => $count]);
                
                // Log bulk delete activity
                try {
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'user_name' => auth()->user()->name,
                        'user_role' => auth()->user()->role,
                        'action' => 'BULK_DELETE',
                        'module' => 'APPLICATION',
                        'description' => "Bulk deleted {$count} applications",
                        'old_data' => null,
                        'new_data' => json_encode([
                            'action' => 'bulk_delete',
                            'count' => $count,
                            'application_ids' => $applicationIds
                        ]),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'url' => $request->fullUrl(),
                        'method' => $request->method()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to log bulk delete activity: ' . $e->getMessage());
                }
                break;
                
            default:
                $message = 'No action performed.';
        }
        
        return redirect()->route('admin.applications.index')
            ->with('success', $message);
    }
    
    /**
     * Old approve method for backward compatibility
     */
    public function oldApprove(Application $application)
    {
        Log::info('Old Approve method called', [
            'application_id' => $application->id,
            'user_id' => auth()->id()
        ]);
        
        // Check payment status using simple method
        if (!$this->canApproveApplicationSimple($application)) {
            $errorMessage = $this->getApprovalErrorMessageSimple($application);
            Log::warning('Old approve failed: ' . $errorMessage, ['application_id' => $application->id]);
            return back()->with('error', 'Cannot approve. ' . $errorMessage);
        }
        
        try {
            $oldStatus = $application->status;
            $application->update(['status' => 'approved']);
            
            // Log this activity in ActivityLog table
            try {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'APPROVE',
                    'module' => 'APPLICATION',
                    'description' => "Application #{$application->id} approved via old method",
                    'old_data' => json_encode(['status' => $oldStatus]),
                    'new_data' => json_encode([
                        'application_id' => $application->id,
                        'status' => 'approved',
                        'approved_by' => auth()->user()->name,
                        'approved_at' => now()->toDateTimeString()
                    ]),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log old approval activity: ' . $e->getMessage());
            }
            
            Log::info('Old approve successful:', ['application_id' => $application->id]);
            return back()->with('success','Approved');
        } catch (\Exception $e) {
            Log::error('Old approve error:', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Old reject method for backward compatibility
     */
    public function oldReject(Application $application)
    {
        Log::info('Old Reject method called', [
            'application_id' => $application->id,
            'user_id' => auth()->id()
        ]);
        
        try {
            $oldStatus = $application->status;
            $application->update(['status' => 'rejected']);
            
            // Log this activity in ActivityLog table
            try {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'REJECT',
                    'module' => 'APPLICATION',
                    'description' => "Application #{$application->id} rejected via old method",
                    'old_data' => json_encode(['status' => $oldStatus]),
                    'new_data' => json_encode([
                        'application_id' => $application->id,
                        'status' => 'rejected',
                        'rejected_by' => auth()->user()->name,
                        'rejected_at' => now()->toDateTimeString()
                    ]),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log old rejection activity: ' . $e->getMessage());
            }
            
            Log::info('Old reject successful:', ['application_id' => $application->id]);
            return back()->with('success','Rejected');
        } catch (\Exception $e) {
            Log::error('Old reject error:', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Debug method to check payment status
     */
    public function debugPaymentStatus($id)
    {
        $application = Application::with('invoice')->findOrFail($id);
        
        $debugInfo = [
            'application_id' => $application->id,
            'application_status' => $application->status,
            'application_payment_status' => $application->payment_status,
            'has_invoice' => $application->invoice ? 'Yes' : 'No',
            'invoice_payment_status' => $application->invoice ? $application->invoice->payment_status : 'N/A',
            'invoice_paid_at' => $application->invoice && $application->invoice->paid_at ? $application->invoice->paid_at->format('Y-m-d H:i:s') : 'N/A',
            'can_approve' => $this->canApproveApplicationSimple($application) ? 'Yes' : 'No',
            'approval_error' => $this->canApproveApplicationSimple($application) ? 'None' : $this->getApprovalErrorMessageSimple($application)
        ];
        
        Log::info('Debug Payment Status:', $debugInfo);
        
        return response()->json([
            'success' => true,
            'debug_info' => $debugInfo,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Test approve without payment check (for debugging only)
     */
    public function testApprove($id)
    {
        // Only allow in local environment for testing
        if (!app()->environment('local')) {
            abort(403, 'This method is only available in local environment');
        }
        
        $application = Application::findOrFail($id);
        
        // Generate certificate number
        $certificateNumber = 'CERT-TEST-' . date('Ymd') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
        
        $oldStatus = $application->status;
        $oldPaymentStatus = $application->payment_status;
        
        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'certificate_number' => $certificateNumber,
            'remarks' => 'TEST APPROVAL - Payment check bypassed',
            'payment_status' => 'paid', // Set as paid
        ]);
        
        // Log this activity in ActivityLog table
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'user_role' => auth()->user()->role,
                'action' => 'TEST_APPROVE',
                'module' => 'APPLICATION',
                'description' => "Application #{$application->id} test approved (payment check bypassed)",
                'old_data' => json_encode([
                    'status' => $oldStatus,
                    'payment_status' => $oldPaymentStatus
                ]),
                'new_data' => json_encode([
                    'application_id' => $application->id,
                    'status' => 'approved',
                    'certificate_number' => $certificateNumber,
                    'payment_status' => 'paid',
                    'approved_by' => auth()->user()->name,
                    'test_approval' => true
                ]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log test approval activity: ' . $e->getMessage());
        }
        
        Log::warning('TEST APPROVAL USED', [
            'application_id' => $application->id,
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('admin.applications.show', $id)
            ->with('success', 'TEST: Application #' . $application->id . ' has been approved (payment check bypassed).');
    }
    
    /**
     * Quick fix: Manually update payment status
     */
    public function manualPaymentUpdate($id, Request $request)
    {
        // Only super admin can use this
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Unauthorized access.');
        }
        
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
            'reason' => 'required|string|max:1000',
        ]);
        
        $application = Application::with('invoice')->findOrFail($id);
        
        try {
            // Store old data
            $oldData = [
                'application_payment_status' => $application->payment_status,
                'invoice_payment_status' => $application->invoice ? $application->invoice->payment_status : null
            ];
            
            // Update application payment status
            $application->update([
                'payment_status' => $request->payment_status,
                'remarks' => $application->remarks . "\n[MANUAL UPDATE: " . $request->payment_status . "] Reason: " . $request->reason
            ]);
            
            // Update invoice if exists
            if ($application->invoice) {
                $application->invoice->update([
                    'payment_status' => $request->payment_status,
                    'paid_at' => $request->payment_status === 'paid' ? now() : null,
                    'remarks' => 'Manually updated to ' . $request->payment_status
                ]);
            }
            
            // Log this activity in ActivityLog table
            try {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'MANUAL_PAYMENT_UPDATE',
                    'module' => 'APPLICATION',
                    'description' => "Manual payment status update for application #{$application->id}",
                    'old_data' => json_encode($oldData),
                    'new_data' => json_encode([
                        'application_id' => $application->id,
                        'new_payment_status' => $request->payment_status,
                        'reason' => $request->reason,
                        'updated_by' => auth()->user()->name,
                        'updated_at' => now()->toDateTimeString()
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log manual payment update activity: ' . $e->getMessage());
            }
            
            Log::warning('Manual payment update:', [
                'application_id' => $application->id,
                'new_status' => $request->payment_status,
                'reason' => $request->reason,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('admin.applications.show', $id)
                ->with('success', 'Payment status manually updated to: ' . $request->payment_status);
                
        } catch (\Exception $e) {
            Log::error('Error in manual payment update:', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.applications.show', $id)
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}