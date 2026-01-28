<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())
            ->with(['application.certificateType', 'user'])
            ->latest()
            ->get();

        return view('citizen.invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load(['application.certificateType', 'user']);

        return view('citizen.invoices.show', compact('invoice'));
    }

    /**
     * Generate PDF for download
     */
    public function pdf(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load(['application.certificateType', 'user']);
        
        // Direct MPDF without any Laravel response
        return $this->directMPDFDownload($invoice);
    }

    /**
     * View PDF in browser
     */
    public function view(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load(['application.certificateType', 'user']);
        
        return $this->directMPDFView($invoice);
    }

    /**
     * DIRECT MPDF Download - No Laravel Response
     */
    private function directMPDFDownload($invoice)
    {
        try {
            // Ensure temp directory exists
            $tempDir = storage_path('tmp/mpdf');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Ensure font directory exists
            $fontDir = storage_path('fonts');
            if (!file_exists($fontDir)) {
                mkdir($fontDir, 0755, true);
            }
            
            // Get HTML from Blade
            $html = $this->getInvoiceHTML($invoice);
            
            // Create MPDF instance with SIMPLE config
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'nikosh',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'tempDir' => $tempDir,
                'fontDir' => [$fontDir],
                'fontdata' => [
                    'nikosh' => [
                        'R' => 'Nikosh.ttf',
                        'useOTL' => 0xFF,
                    ],
                    'kalpurush' => [
                        'R' => 'Kalpurush.ttf',
                        'useOTL' => 0xFF,
                    ]
                ],
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $mpdf->SetTitle('চালান - ' . $invoice->invoice_no);
            $mpdf->WriteHTML($html);
            
            // Clean filename
            $filename = 'Invoice-' . $invoice->invoice_no . '.pdf';
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
            
            // DIRECT OUTPUT - No Laravel response wrapper
            $mpdf->Output($filename, 'D');
            exit;
            
        } catch (\Exception $e) {
            // Log error
            error_log('PDF Download Error: ' . $e->getMessage());
            
            // Fallback to view with error
            return redirect()->route('citizen.invoices.show', $invoice)
                ->with('error', 'PDF ডাউনলোড ব্যর্থ: ' . $e->getMessage());
        }
    }

    /**
     * DIRECT MPDF View in Browser
     */
    private function directMPDFView($invoice)
    {
        try {
            // Ensure temp directory exists
            $tempDir = storage_path('tmp/mpdf');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Ensure font directory exists
            $fontDir = storage_path('fonts');
            if (!file_exists($fontDir)) {
                mkdir($fontDir, 0755, true);
            }
            
            // Get HTML from Blade
            $html = $this->getInvoiceHTML($invoice);
            
            // Create MPDF instance
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'nikosh',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'tempDir' => $tempDir,
                'fontDir' => [$fontDir],
                'fontdata' => [
                    'nikosh' => [
                        'R' => 'Nikosh.ttf',
                        'useOTL' => 0xFF,
                    ]
                ],
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $mpdf->SetTitle('চালান - ' . $invoice->invoice_no);
            $mpdf->WriteHTML($html);
            
            // Clean filename
            $filename = 'Invoice-' . $invoice->invoice_no . '.pdf';
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
            
            // DIRECT OUTPUT for browser view
            $mpdf->Output($filename, 'I');
            exit;
            
        } catch (\Exception $e) {
            // Log error
            error_log('PDF View Error: ' . $e->getMessage());
            
            // Fallback
            return redirect()->route('citizen.invoices.show', $invoice)
                ->with('error', 'PDF দেখাতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * Get Invoice HTML (Simplified version)
     */
    private function getInvoiceHTML($invoice)
    {
        // বাংলা সংখ্যা কনভার্টার
        function banglaNumber($number) {
            $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
            $bangla = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '.'];
            return str_replace($english, $bangla, number_format($number, 2));
        }
        
        // বাংলা তারিখ
        function banglaDate($date) {
            if (!$date) return 'তারিখ নেই';
            $banglaMonths = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
            $date = \Carbon\Carbon::parse($date);
            $day = banglaNumber($date->day);
            $month = $banglaMonths[$date->month - 1] ?? 'মাস';
            $year = banglaNumber($date->year);
            return $day . ' ' . $month . ' ' . $year;
        }
        
        // Calculate total
        $totalAmount = $invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0);
        
        // Simple HTML
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>চালান - ' . $invoice->invoice_no . '</title>
            <style>
                body { font-family: Nikosh, sans-serif; font-size: 14px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #2c3e50; }
                .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .info-table td { padding: 8px; border-bottom: 1px solid #ddd; }
                .amount-box { background: #e8f4fc; padding: 15px; text-align: center; font-size: 20px; margin: 20px 0; }
                .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .items-table th, .items-table td { padding: 10px; border: 1px solid #ddd; }
                .items-table th { background: #2c3e50; color: white; }
                .footer { text-align: center; margin-top: 40px; color: #777; }
                .bangla-number { font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>চালান / ইনভয়েস</h1>
                <h2>চালান নম্বর: ' . $invoice->invoice_no . '</h2>
                <p>তারিখ: ' . banglaDate($invoice->created_at) . '</p>
            </div>
            
            <table class="info-table">
                <tr><td><strong>গ্রাহকের নাম:</strong></td><td>' . ($invoice->user->name ?? 'নাম নেই') . '</td></tr>
                <tr><td><strong>ফোন নম্বর:</strong></td><td>' . ($invoice->user->phone ?? 'নম্বর নেই') . '</td></tr>
                <tr><td><strong>সেবার ধরন:</strong></td><td>' . ($invoice->application->certificateType->name ?? 'সনদ') . '</td></tr>
            </table>
            
            <div class="amount-box">
                মোট পরিশোধযোগ্য: <span class="bangla-number">' . banglaNumber($totalAmount) . '</span> ৳
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>ক্রমিক</th>
                        <th>বিবরণ</th>
                        <th>পরিমাণ (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="bangla-number">১</td>
                        <td>' . ($invoice->application->certificateType->name ?? 'সনদ ফি') . '</td>
                        <td class="bangla-number" style="text-align: right;">' . banglaNumber($invoice->amount) . '</td>
                    </tr>';
        
        if (($invoice->vat_amount ?? 0) > 0) {
            $html .= '<tr>
                        <td class="bangla-number">২</td>
                        <td>ভ্যাট</td>
                        <td class="bangla-number" style="text-align: right;">' . banglaNumber($invoice->vat_amount) . '</td>
                    </tr>';
        }
        
        if (($invoice->service_charge ?? 0) > 0) {
            $html .= '<tr>
                        <td class="bangla-number">৩</td>
                        <td>সেবা চার্জ</td>
                        <td class="bangla-number" style="text-align: right;">' . banglaNumber($invoice->service_charge) . '</td>
                    </tr>';
        }
        
        $html .= '<tr style="background: #f0f0f0; font-weight: bold;">
                    <td colspan="2" style="text-align: right;">মোট</td>
                    <td style="text-align: right;" class="bangla-number">' . banglaNumber($totalAmount) . ' ৳</td>
                </tr>
            </tbody>
            </table>
            
            <div style="text-align: center; margin: 30px 0; padding: 20px; border: 2px solid ' . ($invoice->payment_status == 'paid' ? '#27ae60' : '#e74c3c') . '; background: ' . ($invoice->payment_status == 'paid' ? '#d5f4e6' : '#fde8e8') . ';">
                <strong>পেমেন্ট অবস্থা: ' . ($invoice->payment_status == 'paid' ? 'পরিশোধিত' : 'বকেয়া') . '</strong>
            </div>
            
            <div class="footer">
                <p>ইউনিয়ন ডিজিটাল সেবা</p>
                <p>© ' . banglaNumber(date('Y')) . ' - ' . config('app.name', 'ইউনিয়ন ব্যবস্থাপনা') . '</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Process payment - REDIRECT TO PAYMENT PAGE
     */
    public function pay(Request $request, Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        if ($invoice->payment_status === 'paid') {
            return redirect()->back()->with('info', 'পেমেন্ট ইতিমধ্যে সম্পন্ন হয়েছে।');
        }

        // সরাসরি পেমেন্ট পেজে রিডাইরেক্ট করুন
        return redirect()->route('citizen.payments.show', $invoice);
    }

    /**
     * Simple test endpoint
     */
    public function testSimple()
    {
        try {
            // Create test invoice
            $invoice = $this->createTestInvoice();
            return $this->directMPDFDownload($invoice);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Create test invoice
     */
    private function createTestInvoice()
    {
        $invoice = new \stdClass();
        $invoice->id = 1;
        $invoice->invoice_no = 'TEST-' . date('Ymd-His');
        $invoice->amount = 1500;
        $invoice->vat_amount = 225;
        $invoice->service_charge = 100;
        $invoice->payment_status = 'paid';
        $invoice->created_at = now();
        $invoice->paid_at = now();
        
        $user = new \stdClass();
        $user->name = 'আব্দুল করিম সরকার';
        $user->email = 'test@example.com';
        $user->phone = '০১৭১১২২৩৩৪৪';
        
        $certificateType = new \stdClass();
        $certificateType->name = 'মূলনিবাস সনদ';
        
        $application = new \stdClass();
        $application->application_no = 'APP-' . date('Ymd') . '-001';
        $application->certificateType = $certificateType;
        
        $invoice->user = $user;
        $invoice->application = $application;
        
        return $invoice;
    }

    /**
     * Check if fonts exist
     */
    public function checkFonts()
    {
        $fontDir = storage_path('fonts');
        $fonts = [
            'Nikosh.ttf' => file_exists($fontDir . '/Nikosh.ttf'),
            'Kalpurush.ttf' => file_exists($fontDir . '/Kalpurush.ttf'),
        ];
        
        $exists = array_filter($fonts);
        
        if (count($exists) > 0) {
            return response()->json([
                'status' => 'success',
                'fonts' => $fonts,
                'message' => 'ফন্ট পাওয়া গেছে'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'fonts' => $fonts,
                'message' => 'কোন ফন্ট পাওয়া যায়নি'
            ]);
        }
    }

    /**
     * Manual font download helper
     */
    public function manualFontHelp()
    {
        $fontDir = storage_path('fonts');
        
        if (!file_exists($fontDir)) {
            mkdir($fontDir, 0755, true);
        }
        
        $instructions = "
        <h2>বাংলা ফন্ট সেটআপ নির্দেশিকা</h2>
        
        <p><strong>ধাপ ১:</strong> নিচের লিংক থেকে ফন্ট ডাউনলোড করুন:</p>
        <ul>
            <li><a href='https://github.com/bangladeshifont/nikosh-font/raw/main/Nikosh.ttf' target='_blank'>Nikosh.ttf ডাউনলোড</a></li>
            <li><a href='https://github.com/fonts-of-bangladesh/kalpurush/raw/master/kalpurush.ttf' target='_blank'>Kalpurush.ttf ডাউনলোড</a></li>
        </ul>
        
        <p><strong>ধাপ ২:</strong> ডাউনলোডকৃত ফাইলগুলো এই লোকেশনে কপি করুন:</p>
        <pre>" . $fontDir . "</pre>
        
        <p><strong>ধাপ ৩:</strong> ফাইলগুলো কপি করার পর এই লিংকে ক্লিক করুন:</p>
        <p><a href='" . route('citizen.invoices.checkFonts') . "'>ফন্ট চেক করুন</a></p>
        ";
        
        return $instructions;
    }
}