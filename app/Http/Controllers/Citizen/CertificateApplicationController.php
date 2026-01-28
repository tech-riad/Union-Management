<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificateType;
use App\Models\CertificateApplication;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificateApplicationController extends Controller
{
    /**
     * Show certificates & my applications
     */
    public function index()
    {
        $certificates = CertificateType::all(); // Remove is_active condition

        $applications = CertificateApplication::with('certificateType')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('citizen.applications.index', compact('certificates', 'applications'));
    }

    /**
     * Apply form page
     */
    public function apply($certificateId)
    {
        $user = Auth::user();
        $certificate = CertificateType::findOrFail($certificateId);
        
        // Get all certificates
        $allCertificates = CertificateType::all(); // Remove is_active condition

        // Profile completeness check
        // Uncomment if you have profile system
        /*
        if (!$user->profile || !$user->profile->is_complete) {
            return redirect()
                ->route('citizen.profile.edit')
                ->with('warning', 'প্রোফাইল সম্পূর্ণ করুন ❗');
        }
        */

        return view('citizen.applications.apply', compact('certificate', 'allCertificates'));
    }

    /**
     * Store application & generate invoice
     */
    public function store(Request $request, $certificateId)
    {
        // Find certificate
        $certificate = CertificateType::findOrFail($certificateId);
        
        // Get form fields from certificate
        $formFields = [];
        if ($certificate->form_fields) {
            if (is_string($certificate->form_fields)) {
                $formFields = json_decode($certificate->form_fields, true);
            } else {
                $formFields = $certificate->form_fields;
            }
        }
        
        // ---------------- Validation rules ----------------
        $rules = [
            'agree_rules' => 'required|accepted',
        ];

        foreach ($formFields as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldType = $field['type'] ?? 'text';
            $isRequired = $field['required'] ?? false;
            
            if ($fieldType === 'file') {
                $rules[$fieldName] = $isRequired ? 'required|file|max:2048' : 'nullable|file|max:2048';
            } else {
                $rules[$fieldName] = $isRequired ? 'required|string' : 'nullable|string';
            }
        }

        $request->validate($rules);

        // ---------------- Save dynamic form data ----------------
        $formData = [];

        foreach ($formFields as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldType = $field['type'] ?? 'text';
            
            if ($fieldType === 'file' && $request->hasFile($fieldName)) {
                $formData[$fieldName] = $request
                    ->file($fieldName)
                    ->store('certificate_files', 'public');
            } else {
                $formData[$fieldName] = $request->input($fieldName);
            }
        }

        // ---------------- Create Application ----------------
        $application = CertificateApplication::create([
            'user_id'             => Auth::id(),
            'certificate_type_id' => $certificate->id, // ✅ certificate_type_id ব্যবহার করুন
            'form_data'           => $formData,
            'fields'              => $formFields, // ✅ fields column এ store করুন
            'fee'                 => $certificate->fee ?? 0,
            'status'              => 'pending',
            'payment_status'      => 'unpaid',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        // ---------------- Create Invoice ----------------
        $invoice = Invoice::create([
            'application_id' => $application->id,
            'user_id'        => Auth::id(),
            'invoice_no'     => 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'amount'         => $certificate->fee ?? 0,
            'payment_status' => 'unpaid',
            'payment_method' => 'pending',
        ]);

        // ---------------- Update application with invoice_id ----------------
        $application->update(['invoice_id' => $invoice->id]);

        // ---------------- Redirect to applications index ----------------
        return redirect()
            ->route('citizen.applications.index')
            ->with('success', 'আবেদন সফল হয়েছে ✅ ইনভয়েস তৈরি হয়েছে');
    }
    
    /**
     * Show application details
     */
    public function show($id)
    {
        $application = CertificateApplication::with(['certificateType', 'user', 'invoice'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('citizen.applications.show', compact('application'));
    }
    
    /**
     * Generate certificate PDF
     */
    public function certificatePdf($id)
    {
        $application = CertificateApplication::with(['certificateType', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        // Check if application is approved
        if ($application->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Certificate is not approved yet.');
        }
        
        // Your PDF generation logic here
        // return PDF::loadView('certificates.pdf', compact('application'))->stream();
        
        return view('certificates.pdf', compact('application'));
    }
}