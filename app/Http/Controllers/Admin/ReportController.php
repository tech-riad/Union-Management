<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Invoice;
use App\Models\CertificateType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    /**
     * Display main reports page
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Get revenue report data
     */
    public function revenueReport(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $today = Carbon::today();
        $data = [];
        
        if ($period === 'custom' && $startDate && $endDate) {
            $data = $this->getCustomRevenue($startDate, $endDate);
            $data['period'] = 'Custom: ' . Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y');
        } else {
            switch ($period) {
                case 'day':
                    $data = $this->getDailyRevenue($today);
                    break;
                case 'week':
                    $data = $this->getWeeklyRevenue($today);
                    break;
                case 'month':
                    $data = $this->getMonthlyRevenue($today);
                    break;
                case 'year':
                    $data = $this->getYearlyRevenue($today);
                    break;
            }
        }
        
        // Ensure all required keys exist with default values
        $data = array_merge([
            'total' => 0,
            'count' => 0,
            'previous' => 0,
            'period' => '',
            'date' => now()->format('Y-m-d'),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ], $data);
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('admin.reports.revenue', compact('data', 'period', 'startDate', 'endDate'));
    }

    /**
     * Get application report data
     */
    public function applicationReport(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $today = Carbon::today();
        $data = [];
        
        if ($period === 'custom' && $startDate && $endDate) {
            $data = $this->getCustomApplications($startDate, $endDate);
            $data['period'] = 'Custom: ' . Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y');
        } else {
            switch ($period) {
                case 'day':
                    $data = $this->getDailyApplications($today);
                    break;
                case 'week':
                    $data = $this->getWeeklyApplications($today);
                    break;
                case 'month':
                    $data = $this->getMonthlyApplications($today);
                    break;
                case 'year':
                    $data = $this->getYearlyApplications($today);
                    break;
            }
        }
        
        // Ensure all required keys exist with default values
        $data = array_merge([
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'previous' => ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0],
            'period' => '',
            'date' => now()->format('Y-m-d'),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ], $data);
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('admin.reports.applications', compact('data', 'period', 'startDate', 'endDate'));
    }

    /**
     * Export reports - Updated version with period parameter
     */
    public function export($type)
    {
        $format = request()->get('format', 'csv'); // Default to CSV
        $period = request()->get('period', 'month');
        $startDate = request()->get('start_date');
        $endDate = request()->get('end_date');
        
        if ($format === 'csv') {
            // CSV Export
            if ($type === 'revenue') {
                return $this->exportRevenueCsv($period, $startDate, $endDate);
            } elseif ($type === 'applications') {
                return $this->exportApplicationsCsv($period, $startDate, $endDate);
            }
        } elseif ($format === 'pdf') {
            // PDF Export using Mpdf
            if ($type === 'revenue') {
                $data = $this->getRevenueDataByPeriod($period, $startDate, $endDate);
                $html = view('admin.reports.exports.revenue-pdf', [
                    'data' => $data,
                    'period' => $period,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ])->render();
                
                return $this->generatePdf($html, 'revenue-report-' . $period . '-' . date('Y-m-d') . '.pdf');
                
            } elseif ($type === 'applications') {
                $data = $this->getApplicationDataByPeriod($period, $startDate, $endDate);
                $html = view('admin.reports.exports.application-pdf', [
                    'data' => $data,
                    'period' => $period,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ])->render();
                
                return $this->generatePdf($html, 'application-report-' . $period . '-' . date('Y-m-d') . '.pdf');
            }
        }
        
        return redirect()->back()->with('error', 'Invalid export type or format.');
    }

    /**
     * Generate PDF using Mpdf
     */
    private function generatePdf($html, $filename)
    {
        try {
            // Create Mpdf instance with configuration for Bangla font support
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'solaimanlipi',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 5,
                'margin_footer' => 5,
                'orientation' => 'P'
            ]);
            
            // Add Bangla font if available
            $this->addBanglaFont($mpdf);
            
            // Write HTML content
            $mpdf->WriteHTML($html);
            
            // Output PDF
            return response($mpdf->Output($filename, 'I'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Add Bangla font support to Mpdf
     */
    private function addBanglaFont($mpdf)
    {
        try {
            $fontPath = public_path('fonts/solaimanlipi.ttf');
            
            if (file_exists($fontPath)) {
                $mpdf->AddFont('solaimanlipi', '', 'solaimanlipi.php');
                $mpdf->SetFont('solaimanlipi', '', 12);
            } else {
                // If Bangla font not found, use default
                $mpdf->SetFont('dejavusans', '', 10);
            }
        } catch (\Exception $e) {
            // Continue with default font
            $mpdf->SetFont('dejavusans', '', 10);
        }
    }

    /**
     * CSV Export for Revenue
     */
    private function exportRevenueCsv($period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today();
        
        $query = Invoice::where('payment_status', 'paid')
            ->with(['application.user', 'application.certificateType']);
            
        // Apply period filter
        if ($period === 'custom' && $startDate && $endDate) {
            $query->whereDate('paid_at', '>=', $startDate)
                  ->whereDate('paid_at', '<=', $endDate);
        } else {
            switch ($period) {
                case 'today':
                    $query->whereDate('paid_at', $today);
                    break;
                case 'week':
                    $query->whereDate('paid_at', '>=', $today->copy()->subDays(7));
                    break;
                case 'month':
                    $query->whereDate('paid_at', '>=', $today->copy()->subDays(30));
                    break;
                case 'year':
                    $query->whereDate('paid_at', '>=', $today->copy()->startOfYear());
                    break;
            }
        }
        
        $invoices = $query->orderBy('paid_at', 'desc')->get();
        
        $fileName = 'revenue-report-' . $period . '-' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = [
            'Invoice ID', 
            'Application ID', 
            'Citizen Name',
            'Certificate Type',
            'Amount (৳)',
            'Payment Date',
            'Payment Method',
            'Status',
            'Created Date'
        ];
        
        $callback = function() use($invoices, $columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);
            
            foreach ($invoices as $invoice) {
                $row = [
                    $invoice->invoice_number ?? 'N/A',
                    $invoice->application_id,
                    $invoice->application->user->name ?? 'N/A',
                    $invoice->application->certificateType->name ?? 'N/A',
                    number_format($invoice->amount, 2),
                    $invoice->paid_at ? $invoice->paid_at->format('d/m/Y') : 'N/A',
                    $invoice->payment_method ?? 'N/A',
                    ucfirst($invoice->payment_status),
                    $invoice->created_at->format('d/m/Y H:i')
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * CSV Export for Applications
     */
    private function exportApplicationsCsv($period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today();
        
        $query = Application::with(['user', 'certificateType']);
            
        // Apply period filter
        if ($period === 'custom' && $startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } else {
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', $today);
                    break;
                case 'week':
                    $query->whereDate('created_at', '>=', $today->copy()->subDays(7));
                    break;
                case 'month':
                    $query->whereDate('created_at', '>=', $today->copy()->subDays(30));
                    break;
                case 'year':
                    $query->whereDate('created_at', '>=', $today->copy()->startOfYear());
                    break;
            }
        }
        
        $applications = $query->orderBy('created_at', 'desc')->get();
        
        $fileName = 'application-report-' . $period . '-' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = [
            'Application ID',
            'Citizen Name',
            'Email',
            'Phone',
            'Certificate Type',
            'Fee (৳)',
            'Status',
            'Applied Date',
            'Processed Date',
            'Address'
        ];
        
        $callback = function() use($applications, $columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);
            
            foreach ($applications as $application) {
                $row = [
                    $application->id,
                    $application->user->name ?? 'N/A',
                    $application->user->email ?? 'N/A',
                    $application->user->phone ?? 'N/A',
                    $application->certificateType->name ?? 'N/A',
                    number_format($application->certificateType->fee ?? 0, 2),
                    ucfirst($application->status),
                    $application->created_at->format('d/m/Y H:i'),
                    $application->processed_at ? $application->processed_at->format('d/m/Y H:i') : 'Pending',
                    $application->user->address ?? 'N/A'
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get revenue data by period for PDF
     */
    private function getRevenueDataByPeriod($period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today();
        
        if ($period === 'custom' && $startDate && $endDate) {
            return $this->getCustomRevenue($startDate, $endDate);
        }
        
        switch ($period) {
            case 'today':
                return $this->getDailyRevenue($today);
            case 'week':
                return $this->getWeeklyRevenue($today);
            case 'month':
                return $this->getMonthlyRevenue($today);
            case 'year':
                return $this->getYearlyRevenue($today);
            default:
                return $this->getMonthlyRevenue($today);
        }
    }

    /**
     * Get application data by period for PDF
     */
    private function getApplicationDataByPeriod($period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today();
        
        if ($period === 'custom' && $startDate && $endDate) {
            return $this->getCustomApplications($startDate, $endDate);
        }
        
        switch ($period) {
            case 'today':
                return $this->getDailyApplications($today);
            case 'week':
                return $this->getWeeklyApplications($today);
            case 'month':
                return $this->getMonthlyApplications($today);
            case 'year':
                return $this->getYearlyApplications($today);
            default:
                return $this->getMonthlyApplications($today);
        }
    }

    /**
     * Get custom date range revenue
     */
    private function getCustomRevenue($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $previousStart = $start->copy()->subDays($end->diffInDays($start));
        $previousEnd = $start->copy()->subDay();
        
        return [
            'total' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $start)
                ->whereDate('paid_at', '<=', $end)
                ->sum('amount') ?: 0,
            'count' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $start)
                ->whereDate('paid_at', '<=', $end)
                ->count() ?: 0,
            'previous' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $previousStart)
                ->whereDate('paid_at', '<=', $previousEnd)
                ->sum('amount') ?: 0,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Get custom date range applications
     */
    private function getCustomApplications($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $previousStart = $start->copy()->subDays($end->diffInDays($start));
        $previousEnd = $start->copy()->subDay();
        
        return [
            'total' => Application::whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)->count() ?: 0,
            'pending' => Application::where('status', 'pending')
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)->count() ?: 0,
            'approved' => Application::where('status', 'approved')
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)->count() ?: 0,
            'rejected' => Application::where('status', 'rejected')
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)->count() ?: 0,
            'previous' => [
                'total' => Application::whereDate('created_at', '>=', $previousStart)
                    ->whereDate('created_at', '<=', $previousEnd)->count() ?: 0,
                'pending' => Application::where('status', 'pending')
                    ->whereDate('created_at', '>=', $previousStart)
                    ->whereDate('created_at', '<=', $previousEnd)->count() ?: 0,
                'approved' => Application::where('status', 'approved')
                    ->whereDate('created_at', '>=', $previousStart)
                    ->whereDate('created_at', '<=', $previousEnd)->count() ?: 0,
                'rejected' => Application::where('status', 'rejected')
                    ->whereDate('created_at', '>=', $previousStart)
                    ->whereDate('created_at', '<=', $previousEnd)->count() ?: 0,
            ],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Get monthly data for charts
     */
    public function monthlyData()
    {
        $currentYear = Carbon::now()->year;
        
        // Monthly revenue
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthStart = Carbon::create($currentYear, $i, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            
            $monthlyRevenue[$i] = Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $monthStart)
                ->whereDate('paid_at', '<=', $monthEnd)
                ->sum('amount') ?: 0;
        }
        
        // Monthly applications
        $monthlyApplications = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthStart = Carbon::create($currentYear, $i, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            
            $monthlyApplications[$i] = [
                'total' => Application::whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    ->count() ?: 0,
                'pending' => Application::where('status', 'pending')
                    ->whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    ->count() ?: 0,
                'approved' => Application::where('status', 'approved')
                    ->whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    ->count() ?: 0,
                'rejected' => Application::where('status', 'rejected')
                    ->whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    ->count() ?: 0,
            ];
        }
        
        return response()->json([
            'revenue' => $monthlyRevenue,
            'applications' => $monthlyApplications,
            'month_names' => [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
                7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
            ]
        ]);
    }

    /**
     * API: Get revenue data
     */
    public function getRevenueData(Request $request)
    {
        $period = $request->get('period', 'today');
        $today = Carbon::today();
        
        $data = [
            'today' => $this->getDailyRevenueData($today),
            'week' => $this->getWeeklyRevenueData($today),
            'month' => $this->getMonthlyRevenueData($today),
            'year' => $this->getYearlyRevenueData($today),
        ];
        
        return response()->json($data[$period] ?? $data['today']);
    }

    /**
     * API: Get application data
     */
    public function getApplicationData(Request $request)
    {
        $period = $request->get('period', 'today');
        $today = Carbon::today();
        
        $data = [
            'today' => $this->getDailyApplicationData($today),
            'week' => $this->getWeeklyApplicationData($today),
            'month' => $this->getMonthlyApplicationData($today),
            'year' => $this->getYearlyApplicationData($today),
        ];
        
        return response()->json($data[$period] ?? $data['today']);
    }

    /**
     * API: Get dashboard stats
     */
    public function getDashboardStats()
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $sevenDaysAgo = $today->copy()->subDays(7);
        $thirtyDaysAgo = $today->copy()->subDays(30);
        $startOfYear = $today->copy()->startOfYear();
        
        $stats = [
            'revenue' => [
                'today' => $this->getDailyRevenueData($today),
                'week' => $this->getWeeklyRevenueData($today),
                'month' => $this->getMonthlyRevenueData($today),
                'year' => $this->getYearlyRevenueData($today),
            ],
            'applications' => [
                'today' => $this->getDailyApplicationData($today),
                'week' => $this->getWeeklyApplicationData($today),
                'month' => $this->getMonthlyApplicationData($today),
                'year' => $this->getYearlyApplicationData($today),
            ],
            'totals' => [
                'users' => User::count(),
                'certificates' => CertificateType::count(),
                'all_applications' => Application::count(),
                'pending_applications' => Application::where('status', 'pending')->count(),
                'total_revenue' => Invoice::where('payment_status', 'paid')->sum('amount'),
            ]
        ];
        
        return response()->json($stats);
    }

    /**
     * Private helper methods - Keep all existing methods below
     */
    
    private function getDailyRevenue(Carbon $date)
    {
        $today = $date->copy();
        $yesterday = $today->copy()->subDay();
        
        return [
            'total' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', $today)
                ->sum('amount') ?: 0,
            'count' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', $today)
                ->count() ?: 0,
            'yesterday' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', $yesterday)
                ->sum('amount') ?: 0,
            'date' => $today->format('Y-m-d'),
        ];
    }
    
    private function getWeeklyRevenue(Carbon $date)
    {
        $weekStart = $date->copy()->subDays(7);
        $previousWeekStart = $weekStart->copy()->subDays(7);
        
        return [
            'total' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $weekStart)
                ->whereDate('paid_at', '<=', $date)
                ->sum('amount') ?: 0,
            'count' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $weekStart)
                ->whereDate('paid_at', '<=', $date)
                ->count() ?: 0,
            'previous' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $previousWeekStart)
                ->whereDate('paid_at', '<', $weekStart)
                ->sum('amount') ?: 0,
            'period' => $weekStart->format('M d') . ' - ' . $date->format('M d'),
        ];
    }
    
    private function getMonthlyRevenue(Carbon $date)
    {
        $monthStart = $date->copy()->subDays(30);
        $previousMonthStart = $monthStart->copy()->subDays(30);
        
        return [
            'total' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $monthStart)
                ->whereDate('paid_at', '<=', $date)
                ->sum('amount') ?: 0,
            'count' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $monthStart)
                ->whereDate('paid_at', '<=', $date)
                ->count() ?: 0,
            'previous' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $previousMonthStart)
                ->whereDate('paid_at', '<', $monthStart)
                ->sum('amount') ?: 0,
            'period' => $monthStart->format('M d') . ' - ' . $date->format('M d'),
        ];
    }
    
    private function getYearlyRevenue(Carbon $date)
    {
        $yearStart = $date->copy()->startOfYear();
        $previousYearStart = $yearStart->copy()->subYear();
        
        return [
            'total' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $yearStart)
                ->whereDate('paid_at', '<=', $date)
                ->sum('amount') ?: 0,
            'count' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $yearStart)
                ->whereDate('paid_at', '<=', $date)
                ->count() ?: 0,
            'previous' => Invoice::where('payment_status', 'paid')
                ->whereDate('paid_at', '>=', $previousYearStart)
                ->whereDate('paid_at', '<', $yearStart)
                ->sum('amount') ?: 0,
            'period' => $yearStart->format('Y'),
        ];
    }
    
    private function getDailyApplications(Carbon $date)
    {
        $today = $date->copy();
        $yesterday = $today->copy()->subDay();
        
        return [
            'total' => Application::whereDate('created_at', $today)->count() ?: 0,
            'pending' => Application::where('status', 'pending')
                ->whereDate('created_at', $today)->count() ?: 0,
            'approved' => Application::where('status', 'approved')
                ->whereDate('created_at', $today)->count() ?: 0,
            'rejected' => Application::where('status', 'rejected')
                ->whereDate('created_at', $today)->count() ?: 0,
            'yesterday' => [
                'total' => Application::whereDate('created_at', $yesterday)->count() ?: 0,
                'pending' => Application::where('status', 'pending')
                    ->whereDate('created_at', $yesterday)->count() ?: 0,
                'approved' => Application::where('status', 'approved')
                    ->whereDate('created_at', $yesterday)->count() ?: 0,
                'rejected' => Application::where('status', 'rejected')
                    ->whereDate('created_at', $yesterday)->count() ?: 0,
            ],
            'date' => $today->format('Y-m-d'),
        ];
    }
    
    private function getWeeklyApplications(Carbon $date)
    {
        $weekStart = $date->copy()->subDays(7);
        $previousWeekStart = $weekStart->copy()->subDays(7);
        
        return [
            'total' => Application::whereDate('created_at', '>=', $weekStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'pending' => Application::where('status', 'pending')
                ->whereDate('created_at', '>=', $weekStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'approved' => Application::where('status', 'approved')
                ->whereDate('created_at', '>=', $weekStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'rejected' => Application::where('status', 'rejected')
                ->whereDate('created_at', '>=', $weekStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'previous' => [
                'total' => Application::whereDate('created_at', '>=', $previousWeekStart)
                    ->whereDate('created_at', '<', $weekStart)->count() ?: 0,
                'pending' => Application::where('status', 'pending')
                    ->whereDate('created_at', '>=', $previousWeekStart)
                    ->whereDate('created_at', '<', $weekStart)->count() ?: 0,
                'approved' => Application::where('status', 'approved')
                    ->whereDate('created_at', '>=', $previousWeekStart)
                    ->whereDate('created_at', '<', $weekStart)->count() ?: 0,
                'rejected' => Application::where('status', 'rejected')
                    ->whereDate('created_at', '>=', $previousWeekStart)
                    ->whereDate('created_at', '<', $weekStart)->count() ?: 0,
            ],
            'period' => $weekStart->format('M d') . ' - ' . $date->format('M d'),
        ];
    }
    
    private function getMonthlyApplications(Carbon $date)
    {
        $monthStart = $date->copy()->subDays(30);
        $previousMonthStart = $monthStart->copy()->subDays(30);
        
        return [
            'total' => Application::whereDate('created_at', '>=', $monthStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'pending' => Application::where('status', 'pending')
                ->whereDate('created_at', '>=', $monthStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'approved' => Application::where('status', 'approved')
                ->whereDate('created_at', '>=', $monthStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'rejected' => Application::where('status', 'rejected')
                ->whereDate('created_at', '>=', $monthStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'previous' => [
                'total' => Application::whereDate('created_at', '>=', $previousMonthStart)
                    ->whereDate('created_at', '<', $monthStart)->count() ?: 0,
                'pending' => Application::where('status', 'pending')
                    ->whereDate('created_at', '>=', $previousMonthStart)
                    ->whereDate('created_at', '<', $monthStart)->count() ?: 0,
                'approved' => Application::where('status', 'approved')
                    ->whereDate('created_at', '>=', $previousMonthStart)
                    ->whereDate('created_at', '<', $monthStart)->count() ?: 0,
                'rejected' => Application::where('status', 'rejected')
                    ->whereDate('created_at', '>=', $previousMonthStart)
                    ->whereDate('created_at', '<', $monthStart)->count() ?: 0,
            ],
            'period' => $monthStart->format('M d') . ' - ' . $date->format('M d'),
        ];
    }
    
    private function getYearlyApplications(Carbon $date)
    {
        $yearStart = $date->copy()->startOfYear();
        $previousYearStart = $yearStart->copy()->subYear();
        
        return [
            'total' => Application::whereDate('created_at', '>=', $yearStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'pending' => Application::where('status', 'pending')
                ->whereDate('created_at', '>=', $yearStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'approved' => Application::where('status', 'approved')
                ->whereDate('created_at', '>=', $yearStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'rejected' => Application::where('status', 'rejected')
                ->whereDate('created_at', '>=', $yearStart)
                ->whereDate('created_at', '<=', $date)->count() ?: 0,
            'previous' => [
                'total' => Application::whereDate('created_at', '>=', $previousYearStart)
                    ->whereDate('created_at', '<', $yearStart)->count() ?: 0,
                'pending' => Application::where('status', 'pending')
                    ->whereDate('created_at', '>=', $previousYearStart)
                    ->whereDate('created_at', '<', $yearStart)->count() ?: 0,
                'approved' => Application::where('status', 'approved')
                    ->whereDate('created_at', '>=', $previousYearStart)
                    ->whereDate('created_at', '<', $yearStart)->count() ?: 0,
                'rejected' => Application::where('status', 'rejected')
                    ->whereDate('created_at', '>=', $previousYearStart)
                    ->whereDate('created_at', '<', $yearStart)->count() ?: 0,
            ],
            'period' => $yearStart->format('Y'),
        ];
    }
    
    /**
     * Methods for API responses (simplified versions)
     */
    private function getDailyRevenueData(Carbon $date)
    {
        $data = $this->getDailyRevenue($date);
        return [
            'total' => $data['total'],
            'count' => $data['count'],
            'change' => $this->calculateChange($data['total'], $data['yesterday']),
        ];
    }
    
    private function getWeeklyRevenueData(Carbon $date)
    {
        $data = $this->getWeeklyRevenue($date);
        return [
            'total' => $data['total'],
            'count' => $data['count'],
            'change' => $this->calculateChange($data['total'], $data['previous']),
        ];
    }
    
    private function getMonthlyRevenueData(Carbon $date)
    {
        $data = $this->getMonthlyRevenue($date);
        return [
            'total' => $data['total'],
            'count' => $data['count'],
            'change' => $this->calculateChange($data['total'], $data['previous']),
        ];
    }
    
    private function getYearlyRevenueData(Carbon $date)
    {
        $data = $this->getYearlyRevenue($date);
        return [
            'total' => $data['total'],
            'count' => $data['count'],
            'change' => $this->calculateChange($data['total'], $data['previous']),
        ];
    }
    
    private function getDailyApplicationData(Carbon $date)
    {
        $data = $this->getDailyApplications($date);
        return [
            'total' => $data['total'],
            'pending' => $data['pending'],
            'approved' => $data['approved'],
            'rejected' => $data['rejected'],
            'change' => $this->calculateChange($data['total'], $data['yesterday']['total']),
        ];
    }
    
    private function getWeeklyApplicationData(Carbon $date)
    {
        $data = $this->getWeeklyApplications($date);
        return [
            'total' => $data['total'],
            'pending' => $data['pending'],
            'approved' => $data['approved'],
            'rejected' => $data['rejected'],
            'change' => $this->calculateChange($data['total'], $data['previous']['total']),
        ];
    }
    
    private function getMonthlyApplicationData(Carbon $date)
    {
        $data = $this->getMonthlyApplications($date);
        return [
            'total' => $data['total'],
            'pending' => $data['pending'],
            'approved' => $data['approved'],
            'rejected' => $data['rejected'],
            'change' => $this->calculateChange($data['total'], $data['previous']['total']),
        ];
    }
    
    private function getYearlyApplicationData(Carbon $date)
    {
        $data = $this->getYearlyApplications($date);
        return [
            'total' => $data['total'],
            'pending' => $data['pending'],
            'approved' => $data['approved'],
            'rejected' => $data['rejected'],
            'change' => $this->calculateChange($data['total'], $data['previous']['total']),
        ];
    }
    
    /**
     * Calculate percentage change
     */
    private function calculateChange($current, $previous)
    {
        if ($previous > 0) {
            $change = (($current - $previous) / $previous) * 100;
            return [
                'percentage' => round($change, 1),
                'type' => $change >= 0 ? 'increase' : 'decrease',
            ];
        } else {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'type' => 'increase',
            ];
        }
    }
}