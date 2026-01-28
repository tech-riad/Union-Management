<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateType;
use App\Models\Application;

class CertificateApplicationController extends Controller
{
    // ================= APPLY =================
    public function apply($certificateId)
    {
        $certificate = CertificateType::findOrFail($certificateId);
        return view('citizen.applications.apply', compact('certificate'));
    }

    // ================= STORE =================
    public function store(Request $request, $certificateId)
    {
        $certificate = CertificateType::findOrFail($certificateId);

        // 🔐 Dynamic validation rules
        $rules = [
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email',
            'address' => 'required|string|max:500',
            'comments' => 'nullable|string|max:1000',
            'agree_rules' => 'required|accepted',
        ];
        
        foreach ($certificate->form_fields as $field) {
            if (!empty($field['required'])) {
                $rules[$field['name']] = 'required';
            } else {
                $rules[$field['name']] = 'nullable';
            }
        }

        $validated = $request->validate($rules);

        // 📦 Prepare form_data
        $formData = [];

        foreach ($certificate->form_fields as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';

            // 📂 File upload
            if ($type === 'file' && $request->hasFile($name)) {
                $formData[$name] = $request
                    ->file($name)
                    ->store('applications', 'public');
            }
            // ✍️ Text / others
            else {
                $formData[$name] = $request->input($name);
            }
        }

        // 💾 Save application
        $application = Application::create([
            'user_id'            => auth()->id(),
            'certificate_id'     => $certificate->id,
            'form_data'          => json_encode($formData),
            'fee'                => $certificate->fee,
            'status'             => 'pending',
            'payment_status'     => 'unpaid',
        ]);

        return redirect()
            ->route('applications.show', $application)
            ->with('success', 'আবেদন সফলভাবে জমা হয়েছে! ইনভয়েস পেজে রিডাইরেক্ট করা হচ্ছে...');
    }
}