<?php

namespace App\Http\Controllers;

use App\Models\CertificateApplication;
use App\Models\CertificateType;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class CertificateController extends Controller
{
    /**
     * Generate Certificate PDF with QR Code using mPDF
     */
    public function generatePDF($id)
    {
        $application = CertificateApplication::with(['user', 'certificateType'])
            ->findOrFail($id);

        // Check if application is approved
        if ($application->status !== 'approved') {
            abort(403, 'Certificate is not approved yet.');
        }

        // Generate verification URL - Use direct URL to avoid route issues
        $verificationUrl = url('/verify/' . $application->certificate_number);

        // Generate QR code with verification URL - USING SVG FORMAT (no imagick needed)
        $qrCodeBase64 = $this->generateQRCode($verificationUrl);

        // Get form data
        $formData = $this->parseJsonData($application->form_data);

        // Calculate validity date (1 year from approval)
        $validityDate = $application->approved_at
            ? $application->approved_at->addYear()->format('d-m-Y')
            : now()->addYear()->format('d-m-Y');

        // Prepare template data
        $data = [
            'application' => $application,
            'formData' => $formData,
            'verificationUrl' => $verificationUrl,
            'qrCode' => $qrCodeBase64,
            'issueDate' => $application->approved_at ?: now(),
            'validityDate' => $validityDate,
            'unionName' => 'উত্তর গাবতলী',
            'chairmanName' => 'মোঃ রফিকুল ইসলাম',
            'secretaryName' => 'মোঃ আব্দুল করিম',
            'applicant' => (object)[
                'name_bangla' => $formData['name_bangla'] ?? $application->user->name ?? '',
                'father_name_bangla' => $formData['father_name_bangla'] ?? '',
                'mother_name_bangla' => $formData['mother_name_bangla'] ?? '',
                'spouse_name_bangla' => $formData['spouse_name_bangla'] ?? '',
                'permanent_address_bangla' => $formData['permanent_address_bangla'] ?? '',
                'nid_number' => $formData['nid_number'] ?? '',
                'dob' => $formData['date_of_birth'] ?? '',
                'birth_place_bangla' => $formData['birth_place_bangla'] ?? '',
            ],
        ];

        // Determine which template to use based on certificate type
        $templateName = $this->getTemplateName($application->certificate_type_id);

        // Debug: Check if template exists
        if (!view()->exists("certificate_templates.{$templateName}")) {
            \Log::error("Template not found: certificate_templates.{$templateName}");
            $templateName = 'নাগরিকত্ব-সনদ';
        }

        // Render the view
        $html = view("certificate_templates.{$templateName}", $data)->render();

        // Configure mPDF with SIMPLER settings
        $mpdfConfig = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 9,
            'margin_footer' => 9,
            'default_font' => 'dejavusans', // Use default font first
            'tempDir' => storage_path('app/mpdf/tmp'),
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ];

        // Check if Bangla font exists
        $fontPath = public_path('fonts/solaimanlipi.ttf');
        if (file_exists($fontPath)) {
            $mpdfConfig['fontDir'] = [public_path('fonts/')];
            $mpdfConfig['fontdata'] = [
                'solaimanlipi' => [
                    'R' => 'solaimanlipi.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ];
            $mpdfConfig['default_font'] = 'solaimanlipi';
        }

        $mpdf = new Mpdf($mpdfConfig);

        // Set PDF metadata
        $mpdf->SetTitle('নাগরিকত্ব সনদ - ' . $application->certificate_number);
        $mpdf->SetAuthor('ইউনিয়ন পরিষদ');

        // Write HTML content
        $mpdf->WriteHTML($html);

        // Output PDF
        $fileName = 'নাগরিকত্ব-সনদ-' . $application->certificate_number . '.pdf';

        return response($mpdf->Output($fileName, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Generate QR Code using SVG format (no imagick required)
     */
    private function generateQRCode($url)
    {
        try {
            // Use SVG format instead of PNG to avoid imagick dependency
            $qrCode = QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->margin(1)
                ->color(0, 0, 0)
                ->backgroundColor(255, 255, 255)
                ->generate($url);

            return 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        } catch (\Exception $e) {
            \Log::error('QR Code Generation Error: ' . $e->getMessage());

            // Fallback SVG if QR generation fails
            return $this->generateFallbackQR($url);
        }
    }

    /**
     * Generate fallback QR code if main generation fails
     */
    private function generateFallbackQR($url)
    {
        // Simple SVG fallback
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="100%" fill="#ffffff"/>
            <rect x="10" y="10" width="180" height="180" fill="none" stroke="#000000" stroke-width="2"/>

            <!-- QR Code Pattern -->
            <rect x="40" y="40" width="20" height="20" fill="#000000"/>
            <rect x="80" y="40" width="20" height="20" fill="#000000"/>
            <rect x="120" y="40" width="20" height="20" fill="#000000"/>

            <rect x="40" y="80" width="20" height="20" fill="#000000"/>
            <rect x="120" y="80" width="20" height="20" fill="#000000"/>

            <rect x="40" y="120" width="20" height="20" fill="#000000"/>
            <rect x="80" y="120" width="20" height="20" fill="#000000"/>
            <rect x="120" y="120" width="20" height="20" fill="#000000"/>

            <!-- Text -->
            <text x="100" y="170" text-anchor="middle" fill="#000000" font-family="Arial" font-size="12">
                VERIFY
            </text>
            <text x="100" y="185" text-anchor="middle" fill="#666666" font-family="Arial" font-size="10">
                ' . substr($url, 0, 15) . '...
            </text>
        </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get template name based on certificate type
     */
    private function getTemplateName($certificateTypeId)
    {
        $certificateType = CertificateType::find($certificateTypeId);

        if (!$certificateType) {
            return 'নাগরিকত্ব-সনদ';
        }

        // Map certificate types to template names
        $templateMap = [
            'নাগরিকত্ব সনদ' => 'নাগরিকত্ব-সনদ',
            'চরিত্র সনদ' => 'চরিত্র-সনদ',
            'ভাতা সনদ' => 'ভাতা-সনদ',
            'আয় সনদ' => 'আয়-সনদ',
            'মৃত্যু সনদ' => 'মৃত্যু-সনদ',
            'স্বামী/স্ত্রীর মৃত্যু সনদ' => 'স্বামী-স্ত্রীর-মৃত্যু-সনদ',
        ];

        return $templateMap[$certificateType->name] ?? 'নাগরিকত্ব-সনদ';
    }

    /**
     * Certificate Verification Page
     */
    public function verify(Request $request, $cid = null)
    {
        // If no certificate number provided, show verification form
        if (!$cid && !$request->has('cid')) {
            return $this->verifyForm();
        }

        // Get certificate number from parameter or request
        $certificateNumber = $cid ?? $request->input('cid') ?? $request->query('cid');

        if (!$certificateNumber) {
            return redirect()->route('certificate.verify.form');
        }

        // Find the application
        $application = CertificateApplication::with([
            'user:id,name,email',
            'certificateType:id,name',
            'approvedBy:id,name'
        ])
        ->where('certificate_number', $certificateNumber)
        ->where('status', 'approved')
        ->first();

        if (!$application) {
            return view('certificates.verify-invalid', [
                'certificateNumber' => $certificateNumber,
                'error' => 'সনদটি পাওয়া যায়নি বা অনুমোদিত নয়'
            ]);
        }

        $formData = $this->parseJsonData($application->form_data);

        // Prepare data for verification page
        $verificationData = [
            'application' => $application,
            'formData' => $formData,
            'isValid' => true,
            'verificationDate' => now()->format('d F, Y h:i A'),
            'verificationId' => 'VER-' . now()->format('YmdHis'),
            'unionName' => 'উত্তর গাবতলী ইউনিয়ন পরিষদ',
            'issueDate' => $application->approved_at->format('d F, Y'),
            'validityDate' => $application->approved_at->addYear()->format('d F, Y'),
        ];

        return view('certificates.verify', $verificationData);
    }

    /**
     * Public verification form
     */
    public function verifyForm()
    {
        return view('certificates.verify-form');
    }

    /**
     * Process verification form submission
     */
    public function processVerify(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|string|max:100'
        ]);

        return redirect()->route('certificate.verify', ['cid' => $request->certificate_number]);
    }

    /**
     * Verify Certificate API (for external systems)
     */
    public function verifyAPI($certificateNumber)
    {
        $application = CertificateApplication::with(['user', 'certificateType'])
            ->where('certificate_number', $certificateNumber)
            ->where('status', 'approved')
            ->first();

        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'সার্টিফিকেট নম্বরt found or not approved',
                'certificate_number' => $certificateNumber,
                'valid' => false
            ], 404);
        }

        $formData = $this->parseJsonData($application->form_data);

        return response()->json([
            'success' => true,
            'valid' => true,
            'certificate' => [
                'number' => $application->certificate_number,
                'type' => $application->certificateType->name ?? 'N/A',
                'type_bangla' => $application->certificateType->name_bangla ?? 'নাগরিকত্ব সনদ',
                'issue_date' => $application->approved_at->format('Y-m-d'),
                'issue_date_bangla' => $application->approved_at->format('d F, Y'),
                'applicant_name' => $application->user->name ?? 'N/A',
                'applicant_name_bangla' => $formData['name_bangla'] ?? 'N/A',
                'father_name' => $formData['father_name_bangla'] ?? 'N/A',
                'mother_name' => $formData['mother_name_bangla'] ?? 'N/A',
                'nid_number' => $formData['nid_number'] ?? 'N/A',
                'status' => $application->status,
                'verification_url' => route('certificate.verify', ['cid' => $certificateNumber]),
                'download_url' => route('certificate.pdf', ['id' => $application->id]),
            ],
            'issuing_authority' => [
                'name' => 'উত্তর গাবতলী ইউনিয়ন পরিষদ',
                'chairman' => 'মোঃ রফিকুল ইসলাম',
                'address' => 'উত্তর গাবতলী, গাবতলী, বগুড়া',
            ]
        ]);
    }

    /**
     * Helper method to parse JSON data
     */
    private function parseJsonData($data)
    {
        if (empty($data)) {
            return [];
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        if (is_array($data)) {
            return $data;
        }

        if (is_object($data)) {
            return (array) $data;
        }

        return [];
    }

    /**
     * Test method to check QR code generation
     */
    public function testQR($text = 'TEST-12345')
    {
        try {
            // Test with SVG format
            $qrCode = QrCode::format('svg')
                ->size(200)
                ->generate($text);

            $base64 = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

            return '<html>
                <head>
                    <title>QR Code Test - SVG Format</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .success { color: green; }
                        .error { color: red; }
                        .qr-container { margin: 20px 0; }
                    </style>
                </head>
                <body>
                    <h1>QR Code Test - SVG Format</h1>
                    <p class="success">✓ QR Code package is working with SVG format</p>
                    <p>Text: <strong>' . htmlspecialchars($text) . '</strong></p>

                    <div class="qr-container">
                        <img src="' . $base64 . '" alt="QR Code" width="200" height="200">
                    </div>

                    <p>Base64 Length: ' . strlen($base64) . ' characters</p>
                    <p>Format: SVG (No ImageMagick required)</p>

                    <h2>Next Steps:</h2>
                    <ol>
                        <li><a href="/test-pdf/1">Test PDF Generation with QR</a></li>
                        <li><a href="/verify">Test Verification Form</a></li>
                        <li><a href="/verify/TEST-123">Test Verification Page</a></li>
                    </ol>
                </body>
            </html>';

        } catch (\Exception $e) {
            return '<html>
                <head>
                    <title>QR Code Error</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .error { color: red; }
                        .solution { background: #f0f0f0; padding: 15px; margin: 15px 0; }
                    </style>
                </head>
                <body>
                    <h1>QR Code Error</h1>
                    <p class="error">Error: ' . $e->getMessage() . '</p>

                    <div class="solution">
                        <h3>Solution:</h3>
                        <p>1. Make sure QR Code package is installed:</p>
                        <pre>composer require simplesoftwareio/simple-qrcode</pre>

                        <p>2. Clear cache:</p>
                        <pre>
php artisan config:clear
php artisan cache:clear
php artisan view:clear
                        </pre>

                        <p>3. Check if package is loaded:</p>
                        <pre>
composer show | grep qrcode
                        </pre>
                    </div>
                </body>
            </html>';
        }
    }
}
