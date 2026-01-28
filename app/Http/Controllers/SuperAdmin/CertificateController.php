<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificateType;

class CertificateController extends Controller
{
    // Available form fields with their types and labels
    private $availableFields = [
        'name_bangla' => [
            'label' => 'নাম (বাংলা)',
            'type' => 'text',
            'required' => true,
            'group' => 'ব্যক্তিগত তথ্য'
        ],
        'name_english' => [
            'label' => 'নাম (ইংরেজি)',
            'type' => 'text',
            'required' => false,
            'group' => 'ব্যক্তিগত তথ্য'
        ],
        'father_name_bangla' => [
            'label' => 'পিতার নাম (বাংলা)',
            'type' => 'text',
            'required' => true,
            'group' => 'পারিবারিক তথ্য'
        ],
        'father_name_english' => [
            'label' => 'পিতার নাম (ইংরেজি)',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'mother_name_bangla' => [
            'label' => 'মাতার নাম (বাংলা)',
            'type' => 'text',
            'required' => true,
            'group' => 'পারিবারিক তথ্য'
        ],
        'mother_name_english' => [
            'label' => 'মাতার নাম (ইংরেজি)',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'permanent_address_bangla' => [
            'label' => 'স্থায়ী ঠিকানা (বাংলা)',
            'type' => 'textarea',
            'required' => true,
            'group' => 'ঠিকানা'
        ],
        'permanent_address_english' => [
            'label' => 'স্থায়ী ঠিকানা (ইংরেজি)',
            'type' => 'textarea',
            'required' => false,
            'group' => 'ঠিকানা'
        ],
        'present_address_bangla' => [
            'label' => 'বর্তমান ঠিকানা (বাংলা)',
            'type' => 'textarea',
            'required' => false,
            'group' => 'ঠিকানা'
        ],
        'present_address_english' => [
            'label' => 'বর্তমান ঠিকানা (ইংরেজি)',
            'type' => 'textarea',
            'required' => false,
            'group' => 'ঠিকানা'
        ],
        'nid_number' => [
            'label' => 'জাতীয় পরিচয়পত্র নম্বর',
            'type' => 'text',
            'required' => true,
            'group' => 'আইডেন্টিফিকেশন'
        ],
        'dob' => [
            'label' => 'জন্ম তারিখ',
            'type' => 'date',
            'required' => true,
            'group' => 'ব্যক্তিগত তথ্য'
        ],
        'business_name_bangla' => [
            'label' => 'ব্যবসার নাম (বাংলা)',
            'type' => 'text',
            'required' => false,
            'group' => 'ব্যবসার তথ্য'
        ],
        'business_name_english' => [
            'label' => 'ব্যবসার নাম (ইংরেজি)',
            'type' => 'text',
            'required' => false,
            'group' => 'ব্যবসার তথ্য'
        ],
        'business_address_bangla' => [
            'label' => 'ব্যবসার ঠিকানা (বাংলা)',
            'type' => 'textarea',
            'required' => false,
            'group' => 'ব্যবসার তথ্য'
        ],
        'business_address_english' => [
            'label' => 'ব্যবসার ঠিকানা (ইংরেজি)',
            'type' => 'textarea',
            'required' => false,
            'group' => 'ব্যবসার তথ্য'
        ],
        'father_nid' => [
            'label' => 'পিতার এনআইডি',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'mother_nid' => [
            'label' => 'মাতার এনআইডি',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'wife_name_bangla' => [
            'label' => 'স্ত্রীর নাম (বাংলা)',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'wife_name_english' => [
            'label' => 'স্ত্রীর নাম (ইংরেজি)',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'photo_upload' => [
            'label' => 'ছবি আপলোড',
            'type' => 'file',
            'required' => false,
            'group' => 'ডকুমেন্ট'
        ],
        'doc1' => [
            'label' => 'ডকুমেন্ট ১',
            'type' => 'file',
            'required' => false,
            'group' => 'ডকুমেন্ট'
        ],
        'doc2' => [
            'label' => 'ডকুমেন্ট ২',
            'type' => 'file',
            'required' => false,
            'group' => 'ডকুমেন্ট'
        ],
        'doc3' => [
            'label' => 'ডকুমেন্ট ৩',
            'type' => 'file',
            'required' => false,
            'group' => 'ডকুমেন্ট'
        ],
        'doc4' => [
            'label' => 'ডকুমেন্ট ৪',
            'type' => 'file',
            'required' => false,
            'group' => 'ডকুমেন্ট'
        ],
        'hasbent_wife_nid' => [
            'label' => 'স্বামীর এনআইডি',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'medical_doc1' => [
            'label' => 'মেডিকেল সার্টিফিকেট ১',
            'type' => 'file',
            'required' => false,
            'group' => 'মেডিকেল'
        ],
        'medical_doc2' => [
            'label' => 'মেডিকেল সার্টিফিকেট ২',
            'type' => 'file',
            'required' => false,
            'group' => 'মেডিকেল'
        ],
        'medical_doc3' => [
            'label' => 'মেডিকেল সার্টিফিকেট ৩',
            'type' => 'file',
            'required' => false,
            'group' => 'মেডিকেল'
        ],
        'kabin_nama' => [
            'label' => 'কাবিননামা',
            'type' => 'file',
            'required' => false,
            'group' => 'বিবাহ'
        ],
        'family_members' => [
            'label' => 'পরিবারের সদস্য সংখ্যা',
            'type' => 'number',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'monthly_income' => [
            'label' => 'মাসিক আয়',
            'type' => 'number',
            'required' => false,
            'group' => 'আর্থিক তথ্য'
        ],
        'guardian_name' => [
            'label' => 'অভিভাবকের নাম',
            'type' => 'text',
            'required' => false,
            'group' => 'পারিবারিক তথ্য'
        ],
        'first_spouse_info' => [
            'label' => 'প্রথম স্ত্রী/স্বামীর তথ্য',
            'type' => 'textarea',
            'required' => false,
            'group' => 'বিবাহ'
        ]
    ];

    // Template mapping with default fields and settings
    private $templateMapping = [
        'নাগরিকত্ব সনদ' => [
            'file' => 'citizenship.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'mother_name_bangla', 
                               'permanent_address_bangla', 'nid_number', 'dob'],
            'signatures' => ['চেয়ারম্যান', 'সচিব'],
            'serial_prefix' => 'CITIZEN',
            'validity_days' => 365,
            'dimensions' => '210,297'
        ],
        'ট্রেড লাইসেন্স' => [
            'file' => 'trade-license.blade.php',
            'default_fields' => ['name_bangla', 'business_name_bangla', 'business_address_bangla', 
                               'nid_number'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'বাণিজ্য কর্মকর্তা'],
            'serial_prefix' => 'TRADE',
            'validity_days' => 365,
            'dimensions' => '210,297'
        ],
        'ওয়ারিশান সনদ' => [
            'file' => 'inheritance.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                               'permanent_address_bangla'],
            'signatures' => ['চেয়ারম্যান', 'সচিব'],
            'serial_prefix' => 'INHRT',
            'validity_days' => null,
            'dimensions' => '210,297'
        ],
        'ভূমিহীন সনদ' => [
            'file' => 'landless.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                               'nid_number'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'উপজেলা কর্মকর্তা'],
            'serial_prefix' => 'LANDL',
            'validity_days' => 180,
            'dimensions' => '210,297'
        ],
        'পারিবারিক সনদ' => [
            'file' => 'family.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                               'wife_name_bangla', 'permanent_address_bangla'],
            'signatures' => ['চেয়ারম্যান', 'সচিব'],
            'serial_prefix' => 'FAMLY',
            'validity_days' => null,
            'dimensions' => '210,297'
        ],
        'অবিবাহিত সনদ' => [
            'file' => 'unmarried.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                               'permanent_address_bangla', 'dob'],
            'signatures' => ['চেয়ারম্যান', 'সচিব'],
            'serial_prefix' => 'UNMRD',
            'validity_days' => 90,
            'dimensions' => '210,297'
        ],
        'পুনর্বিবাহ না হওয়া সনদ' => [
            'file' => 'remarriage.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'permanent_address_bangla'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'নিবন্ধন কর্মকর্তা'],
            'serial_prefix' => 'REMAR',
            'validity_days' => null,
            'dimensions' => '210,297'
        ],
        'একই নামের প্রত্যয়ন' => [
            'file' => 'same-name.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                               'nid_number'],
            'signatures' => ['চেয়ারম্যান', 'সচিব'],
            'serial_prefix' => 'SNAME',
            'validity_days' => null,
            'dimensions' => '210,297'
        ],
        'প্রতিবন্ধী সনদপত্র' => [
            'file' => 'disabled.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                               'nid_number', 'dob'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'চিকিৎসা কর্মকর্তা'],
            'serial_prefix' => 'DISAB',
            'validity_days' => 365,
            'dimensions' => '210,297'
        ],
        'অর্থনৈতিক অসচ্ছলতার সনদপত্র' => [
            'file' => 'economic.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                               'nid_number', 'family_members'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'সামাজিক কর্মকর্তা'],
            'serial_prefix' => 'ECONO',
            'validity_days' => 180,
            'dimensions' => '210,297'
        ],
        'বিবাহিত সনদ' => [
            'file' => 'married.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'wife_name_bangla',
                               'permanent_address_bangla'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'নিকাহ রেজিস্ট্রার'],
            'serial_prefix' => 'MRRED',
            'validity_days' => null,
            'dimensions' => '210,297'
        ],
        'দ্বিতীয় বিবাহের অনুমতি পত্র' => [
            'file' => 'second-marriage.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                               'first_spouse_info'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'উপজেলা নির্বাহী কর্মকর্তা'],
            'serial_prefix' => 'SECMR',
            'validity_days' => 90,
            'dimensions' => '210,297'
        ],
        'নতুন ভোটার প্রত্যয়ন' => [
            'file' => 'new-voter.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                               'permanent_address_bangla', 'dob'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'ভোটার রেজিস্ট্রার'],
            'serial_prefix' => 'VOTER',
            'validity_days' => null,
            'dimensions' => '210,297'
        ],
        'জাতীয়তা সনদ' => [
            'file' => 'nationality.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'mother_name_bangla',
                               'permanent_address_bangla', 'nid_number', 'dob'],
            'signatures' => ['চেয়ারম্যান', 'সচিব'],
            'serial_prefix' => 'NATNL',
            'validity_days' => null,
            'dimensions' => '210,297'
        ],
        'এতিম সনদ' => [
            'file' => 'orphan.blade.php',
            'default_fields' => ['name_bangla', 'permanent_address_bangla', 'dob',
                               'guardian_name'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'সামাজিক কর্মকর্তা'],
            'serial_prefix' => 'ORPHN',
            'validity_days' => 365,
            'dimensions' => '210,297'
        ],
        'মাসিক আয়ের সনদ' => [
            'file' => 'monthly-income.blade.php',
            'default_fields' => ['name_bangla', 'father_name_bangla', 'permanent_address_bangla',
                               'nid_number', 'monthly_income'],
            'signatures' => ['চেয়ারম্যান', 'সচিব', 'উপজেলা কর্মকর্তা'],
            'serial_prefix' => 'INCOM',
            'validity_days' => 180,
            'dimensions' => '210,297'
        ]
    ];

    // Get available fields for public access
    public function getAvailableFields()
    {
        return $this->availableFields;
    }

    // Get template mapping for public access
    public function getTemplateMapping()
    {
        return $this->templateMapping;
    }

    // ---------------- Index ----------------
    public function index()
    {
        // FIXED: Order by ID in ascending order
        $certificates = CertificateType::orderBy('id', 'asc')->get();
        return view('super_admin.certificates.index', compact('certificates'));
    }

    // ---------------- Create ----------------
    public function create()
    {
        $availableFields = $this->availableFields;
        $templates = array_keys($this->templateMapping);
        
        return view('super_admin.certificates.create', compact('availableFields', 'templates'));
    }

    // ---------------- Store ----------------
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:certificate_types,name',
            'fee' => 'required|numeric|min:0',
            'template' => 'required|string|max:255',
            'validity' => 'required|in:none,yearly',
            'form_fields' => 'nullable|array',
            'signatures' => 'nullable|array',
            'serial_prefix' => 'nullable|string|max:10',
            'validity_days' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive'
        ]);

        // Get template config
        $templateConfig = $this->templateMapping[$request->template] ?? null;
        
        // Prepare form_fields as JSON string
        $form_fields = [];
        if ($request->filled('form_fields')) {
            foreach ($request->form_fields as $fieldKey) {
                if (isset($this->availableFields[$fieldKey])) {
                    $fieldConfig = $this->availableFields[$fieldKey];
                    $form_fields[] = [
                        'name' => $fieldKey,
                        'label' => $fieldConfig['label'],
                        'type' => $fieldConfig['type'],
                        'required' => $fieldConfig['required'],
                        'group' => $fieldConfig['group']
                    ];
                }
            }
        } elseif ($templateConfig && isset($templateConfig['default_fields'])) {
            // Use default fields if no custom selection
            foreach ($templateConfig['default_fields'] as $fieldKey) {
                if (isset($this->availableFields[$fieldKey])) {
                    $fieldConfig = $this->availableFields[$fieldKey];
                    $form_fields[] = [
                        'name' => $fieldKey,
                        'label' => $fieldConfig['label'],
                        'type' => $fieldConfig['type'],
                        'required' => $fieldConfig['required'],
                        'group' => $fieldConfig['group']
                    ];
                }
            }
        }

        // Prepare signatures as JSON string
        $signatures = $request->filled('signatures') 
            ? $request->signatures 
            : ($templateConfig['signatures'] ?? ['চেয়ারম্যান', 'সচিব']);

        // Prepare template config as JSON string
        $template_config = [
            'default_fields' => $templateConfig['default_fields'] ?? [],
            'template_file' => $templateConfig['file'] ?? 'default.blade.php',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'dimensions' => $templateConfig['dimensions'] ?? '210,297'
        ];

        // Create certificate type
        CertificateType::create([
            'name' => $request->name,
            'description' => $request->description,
            'fee' => $request->fee,
            'template' => $request->template,
            'template_file' => $templateConfig['file'] ?? 'default.blade.php',
            'template_config' => json_encode($template_config),
            'serial_prefix' => $request->serial_prefix ?? ($templateConfig['serial_prefix'] ?? 'CERT'),
            'validity' => $request->validity,
            'validity_days' => ($request->validity == 'yearly') ? ($request->validity_days ?? 365) : null,
            'signatures' => json_encode($signatures),
            'form_fields' => json_encode($form_fields),
            'status' => $request->status,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('super_admin.certificates.index')
                         ->with('success', 'Certificate type created successfully!');
    }

    // ---------------- Edit ----------------
    public function edit($id)
    {
        $certificate = CertificateType::findOrFail($id);
        $availableFields = $this->availableFields;
        $templates = array_keys($this->templateMapping);
        
        // Extract selected fields - FIXED JSON DECODE ISSUE
        $selectedFields = [];
        if (!empty($certificate->form_fields)) {
            // Check if it's already an array or JSON string
            if (is_array($certificate->form_fields)) {
                $formFields = $certificate->form_fields;
            } else {
                $formFields = json_decode($certificate->form_fields, true);
            }
            
            if (is_array($formFields)) {
                foreach ($formFields as $field) {
                    if (isset($field['name'])) {
                        $selectedFields[] = $field['name'];
                    }
                }
            }
        }

        // Extract signatures - FIXED JSON DECODE ISSUE
        $signatures = [];
        if (!empty($certificate->signatures)) {
            // Check if it's already an array or JSON string
            if (is_array($certificate->signatures)) {
                $signatures = $certificate->signatures;
            } else {
                $signatures = json_decode($certificate->signatures, true);
            }
            
            if (!is_array($signatures) || empty($signatures)) {
                $signatures = ['চেয়ারম্যান', 'সচিব'];
            }
        } else {
            $signatures = ['চেয়ারম্যান', 'সচিব'];
        }

        return view('super_admin.certificates.edit', compact(
            'certificate', 
            'availableFields', 
            'templates', 
            'selectedFields',
            'signatures'
        ));
    }

    // ---------------- Update ----------------
    public function update(Request $request, $id)
    {
        $certificate = CertificateType::findOrFail($id);

        // FIXED: Using correct validation syntax to ignore current record
        $request->validate([
            'name' => 'required|string|max:255|unique:certificate_types,name,' . $id . ',id',
            'fee' => 'required|numeric|min:0',
            'template' => 'required|string|max:255',
            'validity' => 'required|in:none,yearly',
            'form_fields' => 'nullable|array',
            'signatures' => 'nullable|array',
            'serial_prefix' => 'nullable|string|max:10',
            'validity_days' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive'
        ]);

        // Get template config
        $templateConfig = $this->templateMapping[$request->template] ?? null;
        
        // Prepare form_fields as JSON string
        $form_fields = [];
        if ($request->filled('form_fields')) {
            foreach ($request->form_fields as $fieldKey) {
                if (isset($this->availableFields[$fieldKey])) {
                    $fieldConfig = $this->availableFields[$fieldKey];
                    $form_fields[] = [
                        'name' => $fieldKey,
                        'label' => $fieldConfig['label'],
                        'type' => $fieldConfig['type'],
                        'required' => $fieldConfig['required'],
                        'group' => $fieldConfig['group']
                    ];
                }
            }
        } elseif ($templateConfig && isset($templateConfig['default_fields'])) {
            // Use default fields if no custom selection
            foreach ($templateConfig['default_fields'] as $fieldKey) {
                if (isset($this->availableFields[$fieldKey])) {
                    $fieldConfig = $this->availableFields[$fieldKey];
                    $form_fields[] = [
                        'name' => $fieldKey,
                        'label' => $fieldConfig['label'],
                        'type' => $fieldConfig['type'],
                        'required' => $fieldConfig['required'],
                        'group' => $fieldConfig['group']
                    ];
                }
            }
        }

        // Prepare signatures as JSON string
        $signatures = $request->filled('signatures') 
            ? $request->signatures 
            : ($templateConfig['signatures'] ?? ['চেয়ারম্যান', 'সচিব']);

        // Prepare template config as JSON string
        $template_config = [
            'default_fields' => $templateConfig['default_fields'] ?? [],
            'template_file' => $templateConfig['file'] ?? 'default.blade.php',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'dimensions' => $templateConfig['dimensions'] ?? '210,297'
        ];

        // Update certificate type
        $certificate->update([
            'name' => $request->name,
            'description' => $request->description,
            'fee' => $request->fee,
            'template' => $request->template,
            'template_file' => $templateConfig['file'] ?? 'default.blade.php',
            'template_config' => json_encode($template_config),
            'serial_prefix' => $request->serial_prefix ?? ($templateConfig['serial_prefix'] ?? 'CERT'),
            'validity' => $request->validity,
            'validity_days' => ($request->validity == 'yearly') ? ($request->validity_days ?? 365) : null,
            'signatures' => json_encode($signatures),
            'form_fields' => json_encode($form_fields),
            'status' => $request->status,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('super_admin.certificates.index')
                         ->with('success', 'Certificate type updated successfully!');
    }

    // ---------------- Delete ----------------
    public function destroy($id)
    {
        $certificate = CertificateType::findOrFail($id);
        
        // Check if certificate has any applications
        if ($certificate->applications()->exists()) {
            return back()->with('error', 'Cannot delete certificate type because it has associated applications!');
        }
        
        $certificate->delete();
        
        return redirect()->route('super_admin.certificates.index')
                         ->with('success', 'Certificate type deleted successfully!');
    }

    // ---------------- Toggle Status ----------------
    public function toggleStatus($id)
    {
        $certificate = CertificateType::findOrFail($id);
        $certificate->status = ($certificate->status == 'active') ? 'inactive' : 'active';
        $certificate->save();
        
        return back()->with('success', 'Status updated successfully!');
    }

    // ---------------- Show ----------------
    public function show($id)
    {
        $certificate = CertificateType::with(['applications', 'createdBy', 'updatedBy'])->findOrFail($id);
        
        // Decode JSON fields - FIXED JSON DECODE ISSUE
        $signatures = [];
        if (!empty($certificate->signatures)) {
            if (is_array($certificate->signatures)) {
                $signatures = $certificate->signatures;
            } else {
                $signatures = json_decode($certificate->signatures, true) ?? [];
            }
        }
        
        $formFields = [];
        if (!empty($certificate->form_fields)) {
            if (is_array($certificate->form_fields)) {
                $formFields = $certificate->form_fields;
            } else {
                $formFields = json_decode($certificate->form_fields, true) ?? [];
            }
        }
        
        $templateConfig = [];
        if (!empty($certificate->template_config)) {
            if (is_array($certificate->template_config)) {
                $templateConfig = $certificate->template_config;
            } else {
                $templateConfig = json_decode($certificate->template_config, true) ?? [];
            }
        }
        
        return view('super_admin.certificates.show', compact(
            'certificate',
            'signatures',
            'formFields',
            'templateConfig'
        ));
    }
}