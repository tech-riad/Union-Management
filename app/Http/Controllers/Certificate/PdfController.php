<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificateApplication;
use App\Models\CertificateType;
use App\Models\User;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PdfController extends Controller
{
    /**
     * Generate PDF certificate
     */
    public function generateCertificate($applicationId)
    {
        $application = CertificateApplication::with(['certificateType', 'user'])
            ->findOrFail($applicationId);
            // dd($application);

        // Application data
        $applicantData = is_array($application->form_data)
            ? $application->form_data
            : json_decode($application->form_data, true);

        $certificateType = $application->certificateType;
        $user = $application->user;

        // Get template configuration
        $templateConfig = $certificateType->template_config ?? [];

        // Prepare data for template
        $data = $this->prepareCertificateData($application, $applicantData, $user, $certificateType);

        // Generate HTML content
        $htmlContent = $this->generateHtmlContent($certificateType, $data);

        // Generate PDF using MPDF
        $pdf = $this->generatePdf($htmlContent, $certificateType);

        // Output PDF
        $filename = "certificate-{$application->certificate_number}.pdf";

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
            'Pragma' => 'public',
        ]);
    }

    /**
     * Download PDF certificate
     */
    public function downloadCertificate($applicationId)
    {
        $application = CertificateApplication::with(['certificateType', 'user'])
            ->findOrFail($applicationId);

        // Application data
        $applicantData = is_array($application->form_data)
            ? $application->form_data
            : json_decode($application->form_data, true);

        $certificateType = $application->certificateType;
        $user = $application->user;

        // Prepare data for template
        $data = $this->prepareCertificateData($application, $applicantData, $user, $certificateType);

        // Generate HTML content
        $htmlContent = $this->generateHtmlContent($certificateType, $data);

        // Generate PDF using MPDF
        $pdf = $this->generatePdf($htmlContent, $certificateType);

        // Download PDF
        $filename = "certificate-{$application->certificate_number}.pdf";

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
            'Pragma' => 'public',
        ]);
    }

    /**
     * Prepare certificate data
     */
    private function prepareCertificateData($application, $applicantData, $user, $certificateType)
    {
        // Format dates
        $issueDate = $application->approved_at
            ? $application->approved_at->format('d-m-Y')
            : date('d-m-Y');

        $validityDate = null;
        if ($certificateType->validity === 'yearly' && $certificateType->validity_days) {
            $validityDate = $application->approved_at
                ? $application->approved_at->addDays($certificateType->validity_days)->format('d-m-Y')
                : date('d-m-Y', strtotime("+{$certificateType->validity_days} days"));
        }

        // Prepare applicant information
        $applicant = (object) array_merge([
            'name_bangla' => '',
            'name_english' => '',
            'father_name_bangla' => '',
            'mother_name_bangla' => '',
            'permanent_address_bangla' => '',
            'nid_number' => '',
            'dob' => '',
            'business_name_bangla' => '',
            'business_address_bangla' => '',
            'wife_name_bangla' => '',
        ], $applicantData);

        // Union information (এই তথ্যগুলো database থেকে নিয়ে আসতে পারেন)
        $unionInfo = [
            'name' => 'আপনার ইউনিয়ন পরিষদ',
            'address' => 'ইউনিয়ন পরিষদ, আপনার থানা, আপনার জেলা',
            'chairman' => 'জনাব মোঃ আব্দুল করিম',
            'secretary' => 'জনাব মোঃ রফিকুল ইসলাম',
            'phone' => '০১৭১২৩৪৫৬৭৮',
            'email' => 'union@example.com',
        ];

        return [
            // Applicant information
            'applicant' => $applicant,
            'user' => $user,

            // Certificate information
            'certificate_number' => $application->certificate_number ?? 'CERT-' . $application->id,
            'certificate_type' => $certificateType->name,
            'certificate_type_bangla' => $certificateType->template,
            'issue_date' => $issueDate,
            'validity_date' => $validityDate,
            'fee_paid' => $certificateType->fee,

            // Union information
            'union_name' => $unionInfo['name'],
            'union_address' => $unionInfo['address'],
            'chairman_name' => $unionInfo['chairman'],
            'secretary_name' => $unionInfo['secretary'],
            'union_phone' => $unionInfo['phone'],
            'union_email' => $unionInfo['email'],

            // System information
            'generated_date' => date('d-m-Y H:i:s'),
            'qr_code_data' => url("/verify/certificate/{$application->id}"),

            // Signatures
            'signatures' => $certificateType->signature_names,
            'show_signatures' => count($certificateType->signature_names) > 0,

            // Watermark (if enabled)
            'watermark_enabled' => $certificateType->pdf_settings['watermark_enabled'] ?? false,
            'watermark_text' => $certificateType->name . ' - ' . $unionInfo['name'],
        ];
    }

    /**
     * Generate HTML content for certificate
     */
    private function generateHtmlContent($certificateType, $data)
    {
        // dd($data);
        $templateView = $certificateType->template_view_path;
        // dd($templateView);

        // Check if template view exists
        if (!view()->exists($templateView)) {
            dd('view not found: ' . $templateView);
            $templateView = 'certificate_templates.default';
            dd('view not found, using default: ' . $templateView);
        }

        try {
            // Render the blade template
            $html = view($templateView, $data)->render();

            // Fix relative paths for images
            $html = $this->fixRelativePaths($html);

            return $html;
        } catch (\Exception $e) {
            // Fallback to basic template
            return $this->generateFallbackHtml($certificateType, $data);
        }
    }

    /**
     * Fix relative paths in HTML
     */
    private function fixRelativePaths($html)
    {
        // Replace relative image paths with absolute paths
        $baseUrl = url('/');
        $html = preg_replace(
            '/(src|href)=["\'](?!http|https|data:|\/\/)([^"\']+)["\']/',
            '$1="' . $baseUrl . '/$2"',
            $html
        );

        // Fix CSS background images
        $html = preg_replace(
            '/url\(([^)]+)\)/',
            'url(' . $baseUrl . '/$1)',
            $html
        );

        return $html;
    }

    /**
     * Generate fallback HTML template
     */
    private function generateFallbackHtml($certificateType, $data)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . $data['certificate_type'] . '</title>
            <style>
                body {
                    font-family: "SolaimanLipi", Arial, sans-serif;
                    direction: ltr;
                    margin: 0;
                    padding: 20px;
                }
                .certificate-container {
                    border: 3px double #000;
                    padding: 30px;
                    max-width: 21cm;
                    margin: 0 auto;
                    position: relative;
                    min-height: 29.7cm;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #000;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .header h1 {
                    color: #000;
                    font-size: 28px;
                    margin: 0 0 10px 0;
                }
                .header h3 {
                    color: #333;
                    font-size: 18px;
                    margin: 0;
                }
                .content {
                    font-size: 14px;
                    line-height: 1.8;
                    margin-bottom: 40px;
                }
                .applicant-info {
                    background: #f9f9f9;
                    padding: 15px;
                    border-left: 4px solid #007bff;
                    margin: 20px 0;
                }
                .signature-section {
                    margin-top: 60px;
                    padding-top: 20px;
                    border-top: 1px dashed #000;
                }
                .signature-row {
                    display: flex;
                    justify-content: space-around;
                    margin-top: 40px;
                }
                .signature-box {
                    text-align: center;
                    min-width: 150px;
                }
                .signature-line {
                    border-top: 1px solid #000;
                    width: 150px;
                    margin: 5px auto;
                    padding-top: 30px;
                }
                .footer {
                    text-align: center;
                    margin-top: 40px;
                    font-size: 12px;
                    color: #666;
                }
                .certificate-number {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: #f0f0f0;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-size: 12px;
                }
                @page {
                    margin: 0;
                }
            </style>
        </head>
        <body>
            <div class="certificate-container">
                <div class="certificate-number">
                    সনদ নং: ' . $data['certificate_number'] . '
                </div>

                <div class="header">
                    <h1>' . $data['certificate_type_bangla'] . '</h1>
                    <h3>' . $data['union_name'] . '</h3>
                    <p>' . $data['union_address'] . '</p>
                </div>

                <div class="content">
                    <p>এই মর্মে প্রত্যয়ন করা যাচ্ছে যে,</p>

                    <div class="applicant-info">
                        <p><strong>নাম:</strong> ' . ($data['applicant']->name_bangla ?? '') . '</p>
                        <p><strong>পিতার নাম:</strong> ' . ($data['applicant']->father_name_bangla ?? '') . '</p>
                        <p><strong>মাতার নাম:</strong> ' . ($data['applicant']->mother_name_bangla ?? '') . '</p>
                        <p><strong>স্থায়ী ঠিকানা:</strong> ' . ($data['applicant']->permanent_address_bangla ?? '') . '</p>
                        <p><strong>এনআইডি নম্বর:</strong> ' . ($data['applicant']->nid_number ?? '') . '</p>
                    </div>

                    <p>উক্ত ব্যক্তি এই ইউনিয়নের একজন আইনানুগ নাগরিক। তার সম্পর্কে এই পরিষদের রেকর্ডে কোন অভিযোগ নেই এবং তিনি এই ইউনিয়নের একজন সুনাগরিক হিসেবে পরিচিত।</p>

                    <p>এই সনদটি প্রদান করা হলো।</p>
                </div>

                ' . ($data['show_signatures'] ? '
                <div class="signature-section">
                    <div class="signature-row">
                        ' . implode('', array_map(function($signature) {
                            return '<div class="signature-box">
                                <div class="signature-line"></div>
                                <p><strong>' . $signature . '</strong></p>
                            </div>';
                        }, $data['signatures'])) . '
                    </div>
                </div>
                ' : '') . '

                <div class="footer">
                    <p>প্রদানের তারিখ: ' . $data['issue_date'] . '</p>
                    ' . ($data['validity_date'] ? '
                    <p>বৈধতার মেয়াদ: ' . $data['validity_date'] . ' পর্যন্ত</p>
                    ' : '
                    <p>বৈধতার মেয়াদ: চিরস্থায়ী</p>
                    ') . '
                    <p>যাচাই লিংক: ' . $data['qr_code_data'] . '</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Generate PDF using MPDF
     */
    private function generatePdf($htmlContent, $certificateType)
    {
        // Get PDF settings
        $pdfSettings = $certificateType->pdf_settings;
        $dimensions = $certificateType->dimensions_array;

        // Default configuration
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // Add Bengali font
        $fontData['solaimanlipi'] = [
            'R' => 'SolaimanLipi.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ];

        // MPDF configuration
        $config = [
            'mode' => 'utf-8',
            'format' => [$dimensions['width'], $dimensions['height']], // Width x Height in mm
            'orientation' => $pdfSettings['orientation'] ?? 'P',
            'default_font' => 'solaimanlipi',
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
                storage_path('fonts'),
            ]),
            'fontdata' => $fontData,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoVietnamese' => true,
            'autoArabic' => true,
            'useSubstitutions' => true,
            'margin_top' => $pdfSettings['margin_top'] ?? 15,
            'margin_bottom' => $pdfSettings['margin_bottom'] ?? 15,
            'margin_left' => $pdfSettings['margin_left'] ?? 15,
            'margin_right' => $pdfSettings['margin_right'] ?? 15,
            'tempDir' => storage_path('app/mpdf/tmp'),
        ];

        try {
            // Create MPDF instance
            $mpdf = new Mpdf($config);

            // Set document information
            $mpdf->SetTitle($certificateType->name);
            $mpdf->SetAuthor('Union Management System');
            $mpdf->SetCreator('Union Management System');
            $mpdf->SetSubject('Certificate');
            $mpdf->SetKeywords($certificateType->name . ', Certificate, Union');

            // Add watermark if enabled
            if (($pdfSettings['watermark_enabled'] ?? false) && !empty($certificateType->watermark_path)) {
                $watermarkPath = $certificateType->watermark_full_path;
                if (file_exists($watermarkPath)) {
                    $mpdf->SetWatermarkImage($watermarkPath, $pdfSettings['watermark_opacity'] ?? 0.1);
                    $mpdf->showWatermarkImage = true;
                }
            }

            // Write HTML content
            $mpdf->WriteHTML($htmlContent);

            // Output PDF
            return $mpdf->Output('', 'S'); // 'S' means return as string

        } catch (\Exception $e) {
            // Log error
            \Log::error('PDF Generation Error: ' . $e->getMessage());

            // Return error message
            return $this->generateErrorPdf($e->getMessage());
        }
    }

    /**
     * Generate error PDF
     */
    private function generateErrorPdf($errorMessage)
    {
        $config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'default_font' => 'dejavusans',
        ];

        $mpdf = new Mpdf($config);

        $errorHtml = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Error - Certificate Generation</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 50px; }
                .error-container {
                    border: 2px solid #f00;
                    padding: 30px;
                    background: #fff0f0;
                    text-align: center;
                }
                h1 { color: #f00; }
                p { color: #333; }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>⚠️ Certificate Generation Error</h1>
                <p>Sorry, there was an error generating the certificate.</p>
                <p><strong>Error:</strong> ' . htmlspecialchars($errorMessage) . '</p>
                <p>Please contact the system administrator.</p>
            </div>
        </body>
        </html>';

        $mpdf->WriteHTML($errorHtml);
        return $mpdf->Output('', 'S');
    }

    /**
     * Preview certificate without saving
     */
    public function previewCertificate(Request $request)
    {
        $request->validate([
            'template' => 'required|string',
            'data' => 'nullable|array',
        ]);

        // Get certificate type by template name
        $certificateType = CertificateType::where('template', $request->template)->first();

        if (!$certificateType) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        // Prepare sample data
        $sampleData = $this->prepareSampleData($certificateType, $request->data ?? []);

        // Generate HTML content
        $htmlContent = $this->generateHtmlContent($certificateType, $sampleData);

        // Generate PDF
        $pdf = $this->generatePdf($htmlContent, $certificateType);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',
        ]);
    }

    /**
     * Prepare sample data for preview
     */
    private function prepareSampleData($certificateType, $customData = [])
    {
        $defaultData = [
            'applicant' => (object) [
                'name_bangla' => 'মোঃ আব্দুল করিম',
                'name_english' => 'Md. Abdul Karim',
                'father_name_bangla' => 'মোঃ আব্দুল মালেক',
                'mother_name_bangla' => 'মোসাম্মৎ জেবুন্নেছা',
                'permanent_address_bangla' => 'গ্রাম: শান্তিনগর, ডাকঘর: শান্তিনগর, উপজেলা: শান্তিপুর, জেলা: নরসিংদী',
                'nid_number' => '1234567890123',
                'dob' => '01-01-1980',
                'business_name_bangla' => 'করিম স্টোর',
                'business_address_bangla' => 'বাজারের দক্ষিণ পাশ, শান্তিনগর বাজার',
                'wife_name_bangla' => 'মোসাম্মৎ সালমা বেগম',
            ],
            'certificate_number' => $certificateType->generateCertificateNumber(12345),
            'certificate_type' => $certificateType->name,
            'certificate_type_bangla' => $certificateType->template,
            'issue_date' => date('d-m-Y'),
            'validity_date' => $certificateType->validity === 'yearly'
                ? date('d-m-Y', strtotime('+1 year'))
                : null,
            'fee_paid' => $certificateType->fee,
            'union_name' => 'শান্তিনগর ইউনিয়ন পরিষদ',
            'union_address' => 'শান্তিনগর, শান্তিপুর, নরসিংদী',
            'chairman_name' => 'জনাব মোঃ আব্দুল করিম',
            'secretary_name' => 'জনাব মোঃ রফিকুল ইসলাম',
            'union_phone' => '০১৭১২৩৪৫৬৭৮',
            'union_email' => 'shantinagar.union@example.com',
            'generated_date' => date('d-m-Y H:i:s'),
            'qr_code_data' => 'https://example.com/verify/certificate/12345',
            'signatures' => $certificateType->signature_names,
            'show_signatures' => true,
            'watermark_enabled' => false,
        ];

        return array_merge($defaultData, $customData);
    }
}
