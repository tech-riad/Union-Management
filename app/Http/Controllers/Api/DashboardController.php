<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats()
    {
        // Total Users
        $totalUsers = DB::table('users')->count();
        
        // Today's Applications
        $todayApplications = DB::table('applications')
            ->whereDate('created_at', Carbon::today())
            ->count();
            
        // Yesterday's Applications
        $yesterdayApplications = DB::table('applications')
            ->whereDate('created_at', Carbon::yesterday())
            ->count();
            
        // Today's Revenue
        $todayRevenue = DB::table('payments')
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'success')
            ->sum('amount');
            
        // Yesterday's Revenue
        $yesterdayRevenue = DB::table('payments')
            ->whereDate('created_at', Carbon::yesterday())
            ->where('status', 'success')
            ->sum('amount');
            
        // Pending Count
        $pendingCount = DB::table('applications')
            ->where('status', 'pending')
            ->count();
            
        // User Growth
        $userGrowth = $this->calculateGrowth($totalUsers, $totalUsers - 50); // Example
        
        // Application Growth
        $applicationGrowth = $this->calculateGrowth($todayApplications, $yesterdayApplications);
        
        // Revenue Growth
        $revenueGrowth = $this->calculateGrowth($todayRevenue, $yesterdayRevenue);
        
        // Chart Data (Last 7 days)
        $applicationsData = $this->getApplicationsChartData();
        $revenueData = $this->getRevenueChartData();
        
        return response()->json([
            'total_users' => $totalUsers,
            'today_applications' => $todayApplications,
            'today_revenue' => $todayRevenue,
            'pending_count' => $pendingCount,
            'user_growth' => $userGrowth,
            'application_growth' => $applicationGrowth,
            'revenue_growth' => $revenueGrowth,
            'charts' => [
                'applications' => $applicationsData,
                'revenue' => $revenueData
            ]
        ]);
    }
    
    public function getPendingApplications()
    {
        $applications = DB::table('applications')
            ->where('status', 'pending')
            ->join('certificates', 'applications.certificate_id', '=', 'certificates.id')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select(
                'applications.id',
                'applications.application_number',
                'users.name as applicant_name',
                'certificates.name as certificate_type',
                'applications.created_at'
            )
            ->orderBy('applications.created_at', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json([
            'pending_count' => $applications->count(),
            'applications' => $applications
        ]);
    }
    
    public function getRecentPayments()
    {
        $payments = DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->select(
                'payments.id',
                'payments.transaction_id',
                'payments.amount',
                'payments.status',
                'payments.payment_method',
                'invoices.invoice_number',
                'payments.created_at'
            )
            ->orderBy('payments.created_at', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json([
            'total' => $payments->count(),
            'payments' => $payments
        ]);
    }
    
    public function getSystemStatus()
    {
        // Check database connection
        try {
            DB::connection()->getPdo();
            $dbStatus = 'active';
        } catch (\Exception $e) {
            $dbStatus = 'error';
        }
        
        // Check payment gateway (simplified)
        $paymentGateway = $this->checkPaymentGateway();
        
        // Check SMS service (simplified)
        $smsService = $this->checkSmsService();
        
        // Check backup status
        $backupStatus = $this->checkBackupStatus();
        
        return response()->json([
            'database_status' => $dbStatus,
            'payment_gateway' => $paymentGateway,
            'sms_service' => $smsService,
            'backup_status' => $backupStatus
        ]);
    }
    
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 2);
    }
    
    private function getApplicationsChartData()
    {
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $count = DB::table('applications')
                ->whereDate('created_at', $date)
                ->count();
                
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getRevenueChartData()
    {
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $revenue = DB::table('payments')
                ->whereDate('created_at', $date)
                ->where('status', 'success')
                ->sum('amount');
                
            $data[] = $revenue;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function checkPaymentGateway()
    {
        // Implement actual payment gateway check
        return 'active'; // Simplified
    }
    
    private function checkSmsService()
    {
        // Implement actual SMS service check
        return 'active'; // Simplified
    }
    
    private function checkBackupStatus()
    {
        // Check if backup directory exists and has recent backups
        $backupPath = storage_path('app/backups');
        
        if (!file_exists($backupPath)) {
            return 'error';
        }
        
        // Check for backups in last 24 hours
        $files = glob($backupPath . '/*.zip');
        
        if (empty($files)) {
            return 'warning';
        }
        
        // Check if any backup is from last 24 hours
        foreach ($files as $file) {
            if (time() - filemtime($file) < 86400) {
                return 'active';
            }
        }
        
        return 'warning';
    }
}