<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CertificateType;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

class CertificateTemplateController extends Controller
{
    public function create(CertificateType $certificate)
    {
        return view('super_admin.certificates.template_create', compact('certificate'));
    }

    public function store(Request $request, CertificateType $certificate)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        CertificateTemplate::updateOrCreate(
            ['certificate_type_id' => $certificate->id],
            [
                'title' => $request->title,
                'body'  => $request->body,
                'is_active' => true,
            ]
        );

        return redirect()
            ->route('super_admin.certificates.index')
            ->with('success', 'Certificate template saved successfully ✅');
    }
}
