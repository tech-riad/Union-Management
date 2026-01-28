<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateType;

class SuperAdminController extends Controller
{
    // List all certificate types
    public function certificateIndex()
    {
        $certificates = CertificateType::all();
        return view('dashboards.super_admin.certificates.index', compact('certificates'));
    }

    // Show create certificate type form
    public function certificateCreate()
    {
        return view('dashboards.super_admin.certificates.create');
    }

    // Store new certificate type
    public function certificateStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:certificate_types,name',
            'fee' => 'required|numeric',
            'form_fields' => 'required|array',
            'template_path' => 'nullable|file|mimes:pdf',
        ]);

        $templatePath = null;
        if($request->hasFile('template_path')){
            $templatePath = $request->file('template_path')->store('templates','public');
        }

        CertificateType::create([
            'name' => $request->name,
            'fee' => $request->fee,
            'form_fields' => $request->form_fields,
            'template_path' => $templatePath,
        ]);

        return redirect()->route('super-admin.certificates')->with('success','Certificate type created!');
    }
}
