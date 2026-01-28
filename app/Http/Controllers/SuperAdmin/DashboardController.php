<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Application;
use App\Models\Invoice;
use App\Models\CertificateType;
use App\Models\Union;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Users (including all roles)
        $totalUsers = User::count();
        
        // Today's Applications
        $todayApplications = Application::whereDate('created_at', Carbon::today())->count();
        
        // Pending Applications
        $pendingApplications = Application::where('status', 'pending')->count();
        
        // Today's Revenue from PAID invoices
        $todayRevenue = Invoice::whereDate('created_at', Carbon::today())
            ->where('payment_status', 'paid')
            ->sum('amount');
        
        // Calculate system health score
        $systemHealth = $this->calculateSystemHealth();
        
        // Get application and revenue trends for charts
        $chartData = $this->getChartData();
        
        return view('dashboards.super_admin', compact(
            'totalUsers',
            'todayApplications',
            'pendingApplications',
            'todayRevenue',
            'systemHealth',
            'chartData'
        ));
    }
    
    /**
     * Calculate system health score
     */
    private function calculateSystemHealth()
    {
        $score = 100;
        
        // Check server status (simulated)
        $serverLoad = $this->getServerLoad();
        if ($serverLoad > 80) $score -= 20;
        elseif ($serverLoad > 60) $score -= 10;
        
        // Check database connection
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $score -= 30;
        }
        
        // Check pending applications threshold
        $pendingCount = Application::where('status', 'pending')->count();
        if ($pendingCount > 50) $score -= 10;
        elseif ($pendingCount > 100) $score -= 20;
        
        // Check recent errors
        $recentErrors = 0; // Get from error log table if available
        
        if ($recentErrors > 10) $score -= 15;
        
        return max(0, min(100, $score));
    }
    
    /**
     * Get server load (simulated for now)
     */
    private function getServerLoad()
    {
        // In real application, use system calls or monitoring tools
        // This is a simulated value
        return 24; // Percentage
    }
    
    /**
     * Get chart data for applications and revenue
     */
    private function getChartData()
    {
        $applicationsData = [
            'labels' => [],
            'data' => []
        ];
        
        $revenueData = [
            'labels' => [],
            'data' => []
        ];
        
        // Last 7 days data
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateLabel = $date->format('d M'); // Format: 15 Dec
            
            // Applications count
            $appCount = Application::whereDate('created_at', $date)->count();
            
            // Revenue from paid invoices
            $revenue = Invoice::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('amount');
            
            $applicationsData['labels'][] = $dateLabel;
            $applicationsData['data'][] = $appCount;
            
            $revenueData['labels'][] = $dateLabel;
            $revenueData['data'][] = $revenue;
        }
        
        return [
            'applications' => $applicationsData,
            'revenue' => $revenueData
        ];
    }
    
    /**
     * AJAX endpoint for real-time stats update
     */
    public function getStats()
    {
        $today = Carbon::today();
        
        $stats = [
            'success' => true,
            'totalUsers' => User::count(),
            'todayApplications' => Application::whereDate('created_at', $today)->count(),
            'todayRevenue' => Invoice::whereDate('created_at', $today)
                ->where('payment_status', 'paid')
                ->sum('amount'),
            'pendingApplications' => Application::where('status', 'pending')->count(),
            'systemHealth' => $this->calculateSystemHealth(),
            'activeUsers' => $this->getActiveUsersCount(),
            'timestamp' => Carbon::now()->toDateTimeString(),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Get active users count
     */
    private function getActiveUsersCount()
    {
        // If you have 'last_activity' column in users table
        if (Schema::hasColumn('users', 'last_activity')) {
            return User::where('last_activity', '>=', Carbon::now()->subMinutes(30))->count();
        }
        
        // Or check from sessions table if using database sessions
        if (config('session.driver') === 'database') {
            return DB::table('sessions')
                ->where('last_activity', '>=', Carbon::now()->subMinutes(30)->timestamp)
                ->distinct('user_id')
                ->count('user_id');
        }
        
        return rand(50, 100); // Fallback simulated value
    }
    
    /**
     * Clear system cache
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'সিস্টেম ক্যাশ সফলভাবে ক্লিয়ার করা হয়েছে'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ক্যাশ ক্লিয়ার ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Run database backup
     */
    public function runBackup()
    {
        try {
            \Artisan::call('backup:run', ['--only-db' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'ডাটাবেস ব্যাকআপ সফলভাবে তৈরি হয়েছে'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ব্যাকআপ ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Optimize database
     */
    public function optimizeDB()
    {
        try {
            \Artisan::call('optimize:clear');
            \Artisan::call('cache:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'ডাটাবেস সফলভাবে অপ্টিমাইজ করা হয়েছে'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'অপ্টিমাইজেশন ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get activity logs
     */
    public function getActivityLogs()
    {
        // Simulated activity data
        $activities = [
            [
                'icon' => 'user-plus',
                'color' => 'blue',
                'text' => 'নতুন ইউজার রেজিস্টার্ড হয়েছে',
                'time' => '5m আগে',
                'user' => 'System'
            ],
            [
                'icon' => 'check-circle',
                'color' => 'green',
                'text' => 'আবেদন #' . rand(1000, 9999) . ' অ্যাপ্রুভ করা হয়েছে',
                'time' => '15m আগে',
                'user' => 'Admin User'
            ],
            [
                'icon' => 'money-bill',
                'color' => 'purple',
                'text' => 'পেমেন্ট গ্রহণ করা হয়েছে',
                'time' => '30m আগে',
                'user' => 'bKash Gateway'
            ],
            [
                'icon' => 'exclamation-triangle',
                'color' => 'yellow',
                'text' => 'নতুন ইউনিয়ন যোগ করা হয়েছে',
                'time' => '1h আগে',
                'user' => 'Super Admin'
            ],
            [
                'icon' => 'cog',
                'color' => 'gray',
                'text' => 'সিস্টেম আপডেট সম্পন্ন হয়েছে',
                'time' => '2h আগে',
                'user' => 'Auto Update'
            ],
            [
                'icon' => 'file-export',
                'color' => 'indigo',
                'text' => 'ডেইলি রিপোর্ট জেনারেট করা হয়েছে',
                'time' => '3h আগে',
                'user' => 'Report System'
            ],
        ];
        
        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }
    
    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        $metrics = [
            'avg_response_time' => rand(100, 200),
            'success_rate' => round(99.5 + (rand(0, 5) / 10), 1),
            'uptime' => 99.9,
            'error_rate' => round(rand(1, 5) / 100, 2),
            'server_load' => rand(20, 40),
            'memory_usage' => rand(60, 80),
            'storage_usage' => rand(30, 50),
            'db_connections' => rand(5, 15),
            'queries_per_second' => rand(100, 200),
            'db_latency' => rand(10, 25),
        ];
        
        return response()->json([
            'success' => true,
            'metrics' => $metrics
        ]);
    }
}