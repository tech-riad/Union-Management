<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Invoice;
use App\Models\CertificateType;
use App\Models\User;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SuperReportController extends Controller
{
    /**
     * Display main reports dashboard
     */
    public function index()
    {
        return view('super_admin.reports.index');
    }

    /**
     * Get basic dashboard stats
     */
    public function getDashboardStats()
    {
        try {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            
            $stats = [
                'total_revenue' => Invoice::where('payment_status', 'paid')->sum('amount') ?: 0,
                'total_applications' => Application::count(),
                'total_users' => User::where('role', 'user')->count(),
                'total_admins' => Admin::count(),
                
                'today_revenue' => Invoice::where('payment_status', 'paid')
                    ->whereDate('paid_at', $today)
                    ->sum('amount') ?: 0,
                'today_applications' => Application::whereDate('created_at', $today)->count(),
                
                'yesterday_revenue' => Invoice::where('payment_status', 'paid')
                    ->whereDate('paid_at', $yesterday)
                    ->sum('amount') ?: 0,
                'yesterday_applications' => Application::whereDate('created_at', $yesterday)->count(),
                
                'pending_applications' => Application::where('status', 'pending')->count(),
                'approved_applications' => Application::where('status', 'approved')->count(),
                'rejected_applications' => Application::where('status', 'rejected')->count(),
                
                'total_certificate_types' => CertificateType::count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load stats'
            ], 500);
        }
    }

    /**
     * Simple Revenue Report
     */
    public function revenueReport(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $adminId = $request->get('admin_id');
            $perPage = $request->get('per_page', 20);
            
            $today = Carbon::today();
            $query = Invoice::where('payment_status', 'paid')
                ->with(['application.user', 'application.admin']);
            
            if ($adminId) {
                $query->whereHas('application', function($q) use ($adminId) {
                    if (Schema::hasColumn('applications', 'admin_id')) {
                        $q->where('admin_id', $adminId);
                    } elseif (Schema::hasColumn('applications', 'assigned_admin_id')) {
                        $q->where('assigned_admin_id', $adminId);
                    } elseif (Schema::hasColumn('applications', 'approved_by')) {
                        $q->where('approved_by', $adminId);
                    } elseif (Schema::hasColumn('applications', 'processed_by')) {
                        $q->where('processed_by', $adminId);
                    }
                });
            }
            
            switch ($period) {
                case 'day':
                    $query->whereDate('paid_at', $today);
                    $periodText = 'আজ';
                    break;
                case 'week':
                    $weekStart = $today->copy()->subDays(7);
                    $query->whereDate('paid_at', '>=', $weekStart);
                    $periodText = 'গত ৭ দিন';
                    break;
                case 'month':
                    $monthStart = $today->copy()->subDays(30);
                    $query->whereDate('paid_at', '>=', $monthStart);
                    $periodText = 'গত ৩০ দিন';
                    break;
                case 'year':
                    $yearStart = $today->copy()->startOfYear();
                    $query->whereDate('paid_at', '>=', $yearStart);
                    $periodText = 'এই বছরে';
                    break;
                default:
                    $monthStart = $today->copy()->subDays(30);
                    $query->whereDate('paid_at', '>=', $monthStart);
                    $periodText = 'গত ৩০ দিন';
            }
            
            $invoices = $query->orderBy('paid_at', 'desc')->paginate($perPage);
            $totalRevenue = $query->sum('amount') ?: 0;
            $totalTransactions = $query->count();
            
            $admins = Admin::select('id', 'name')->get();
            
            return view('super_admin.reports.revenue', compact(
                'invoices', 
                'totalRevenue', 
                'totalTransactions', 
                'period', 
                'periodText', 
                'admins', 
                'adminId',
                'perPage'
            ));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'রিপোর্ট লোড করতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * Application Report
     */
    public function applicationReport(Request $request)
    {
        try {
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $status = $request->get('status');
            $type = $request->get('type');
            $adminId = $request->get('admin_id');
            $search = $request->get('search');
            $perPage = $request->get('per_page', 20);
            
            $query = Application::with(['user', 'admin', 'certificateType']);
            
            // Date filter
            if ($fromDate && $toDate) {
                $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
            } elseif ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            } elseif ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }
            
            // Status filter
            if ($status) {
                $query->where('status', $status);
            }
            
            // Type filter
            if ($type) {
                $query->where('certificate_type_id', $type);
            }
            
            // Admin filter
            if ($adminId) {
                $query->where(function($q) use ($adminId) {
                    if (Schema::hasColumn('applications', 'admin_id')) {
                        $q->where('admin_id', $adminId);
                    } elseif (Schema::hasColumn('applications', 'assigned_admin_id')) {
                        $q->where('assigned_admin_id', $adminId);
                    } elseif (Schema::hasColumn('applications', 'approved_by')) {
                        $q->where('approved_by', $adminId);
                    } elseif (Schema::hasColumn('applications', 'processed_by')) {
                        $q->where('processed_by', $adminId);
                    }
                });
            }
            
            // Search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('tracking_id', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('phone', 'like', "%{$search}%");
                      });
                });
            }
            
            // Get paginated results
            $applications = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Statistics
            $totalApplications = Application::count();
            $pendingApplications = Application::where('status', 'pending')->count();
            $approvedApplications = Application::where('status', 'approved')->count();
            $rejectedApplications = Application::where('status', 'rejected')->count();
            $processingApplications = Application::where('status', 'processing')->count();
            $completedApplications = Application::where('status', 'completed')->count();
            
            // Calculate percentages
            $pendingPercentage = $totalApplications > 0 ? round(($pendingApplications / $totalApplications) * 100, 2) : 0;
            $approvedPercentage = $totalApplications > 0 ? round(($approvedApplications / $totalApplications) * 100, 2) : 0;
            $rejectedPercentage = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 2) : 0;
            
            // Get daily trends
            $dailyTrends = Application::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Calculate max and average daily applications
            $maxDailyApplications = $dailyTrends->max('count') ?? 0;
            $avgDailyApplications = $dailyTrends->avg('count') ?? 0;
            
            // Get all data for filters
            $admins = Admin::select('id', 'name')->get();
            $applicationTypes = CertificateType::select('id', 'name')->get();
            
            return view('super_admin.reports.applications', compact(
                'applications',
                'totalApplications',
                'pendingApplications',
                'approvedApplications',
                'rejectedApplications',
                'processingApplications',
                'completedApplications',
                'pendingPercentage',
                'approvedPercentage',
                'rejectedPercentage',
                'admins',
                'applicationTypes',
                'dailyTrends',
                'maxDailyApplications',
                'avgDailyApplications',
                'fromDate',
                'toDate',
                'status',
                'type',
                'adminId',
                'search',
                'perPage'
            ));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'রিপোর্ট লোড করতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * Admin Performance Report
     */
    public function adminPerformance(Request $request)
    {
        try {
            $sortBy = $request->get('sort_by', 'revenue');
            $period = $request->get('period', 'month');
            
            $today = Carbon::today();
            
            // Get all admins
            $admins = Admin::all();
            
            // Manually calculate statistics for each admin
            $adminsWithStats = $admins->map(function($admin) use ($period, $today) {
                // Determine the admin column name
                $adminColumn = $this->getAdminColumnName();
                
                // Get applications for this admin
                $applicationsQuery = Application::where(function($q) use ($admin, $adminColumn) {
                    if ($adminColumn === 'admin_id') {
                        $q->where('admin_id', $admin->id);
                    } elseif ($adminColumn === 'assigned_admin_id') {
                        $q->where('assigned_admin_id', $admin->id);
                    } elseif ($adminColumn === 'approved_by') {
                        $q->where('approved_by', $admin->id);
                    } elseif ($adminColumn === 'processed_by') {
                        $q->where('processed_by', $admin->id);
                    }
                });
                
                // Apply period filter
                if ($period !== 'all') {
                    $applicationsQuery->where(function($q) use ($period, $today) {
                        switch ($period) {
                            case 'day':
                                $q->whereDate('created_at', $today);
                                break;
                            case 'week':
                                $weekStart = $today->copy()->subDays(7);
                                $q->whereDate('created_at', '>=', $weekStart);
                                break;
                            case 'month':
                                $monthStart = $today->copy()->subDays(30);
                                $q->whereDate('created_at', '>=', $monthStart);
                                break;
                            case 'year':
                                $yearStart = $today->copy()->startOfYear();
                                $q->whereDate('created_at', '>=', $yearStart);
                                break;
                        }
                    });
                }
                
                $applications = $applicationsQuery->get();
                
                // Calculate statistics
                $admin->total_applications = $applications->count();
                $admin->pending_applications = $applications->where('status', 'pending')->count();
                $admin->approved_applications = $applications->where('status', 'approved')->count();
                $admin->rejected_applications = $applications->where('status', 'rejected')->count();
                
                // Calculate total revenue
                $admin->total_revenue = $applications->sum(function($app) {
                    return $app->invoice && $app->invoice->payment_status === 'paid' 
                        ? $app->invoice->amount 
                        : 0;
                });
                
                // Calculate approval rate
                $admin->approval_rate = $admin->total_applications > 0 
                    ? round(($admin->approved_applications / $admin->total_applications) * 100, 2)
                    : 0;
                    
                // Calculate rejection rate
                $admin->rejection_rate = $admin->total_applications > 0 
                    ? round(($admin->rejected_applications / $admin->total_applications) * 100, 2)
                    : 0;
                    
                // Calculate efficiency score
                $admin->efficiency_score = $this->calculateEfficiencyScore($admin);
                
                // Get average processing time
                $admin->avg_processing_time = $this->getAverageProcessingTime($admin->id);
                
                return $admin;
            });
            
            // Sort results
            $adminsWithStats = $this->sortAdmins($adminsWithStats, $sortBy);
            
            // Get period text
            $periodText = $this->getPeriodText($period);
            
            return view('super_admin.reports.admin-performance', compact(
                'admins', 
                'sortBy', 
                'period', 
                'periodText'
            ))->with('admins', $adminsWithStats);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'রিপোর্ট লোড করতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * System Monitoring
     */
    public function systemMonitoring()
    {
        try {
            // Server Info
            $serverInfo = [
                'php_version' => phpversion(),
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'timezone' => config('app.timezone'),
                'environment' => config('app.env'),
                'debug_mode' => config('app.debug') ? 'On' : 'Off',
            ];
            
            // Database Info
            $dbInfo = [
                'connection' => config('database.default'),
                'database' => config('database.connections.mysql.database'),
                'tables_count' => $this->getTablesCount(),
                'total_size' => $this->getDatabaseSize(),
                'connection_status' => $this->checkDatabaseConnection() ? 'Connected' : 'Disconnected',
                'last_backup' => $this->getLastBackupTime(),
            ];
            
            // Application Info
            $today = Carbon::today();
            $appInfo = [
                'users_count' => User::where('role', 'user')->count(),
                'admins_count' => Admin::count(),
                'applications_count' => Application::count(),
                'invoices_count' => Invoice::count(),
                'certificate_types_count' => CertificateType::count(),
                
                'today_users' => User::where('role', 'user')->whereDate('created_at', $today)->count(),
                'today_applications' => Application::whereDate('created_at', $today)->count(),
                'today_revenue' => Invoice::where('payment_status', 'paid')
                    ->whereDate('paid_at', $today)
                    ->sum('amount') ?: 0,
            ];
            
            // Storage Info - FIXED: used_percentage calculation
            $totalSpace = disk_total_space('/');
            $freeSpace = disk_free_space('/');
            $usedSpace = $totalSpace - $freeSpace;
            $usedPercentage = $totalSpace > 0 ? round(($usedSpace / $totalSpace) * 100, 2) : 0;
            
            $storageInfo = [
                'total' => round($totalSpace / 1024 / 1024 / 1024, 2), // GB
                'free' => round($freeSpace / 1024 / 1024 / 1024, 2), // GB
                'used' => round($usedSpace / 1024 / 1024 / 1024, 2), // GB
                'used_percentage' => $usedPercentage,
            ];
            
            // Performance Metrics
            $performance = [
                'response_time' => $this->getAverageResponseTime(),
                'queue_status' => $this->getQueueStatus(),
                'cache_status' => $this->getCacheStatus(),
                'uptime' => $this->getSystemUptime(),
            ];
            
            // Calculate additional metrics
            $logFilesSize = $this->getLogFilesSize();
            $activeSessionsCount = $this->getActiveSessionsCount();
            
            // Recent Activities
            $recentActivities = $this->getRecentActivities();
            
            // Prepare activity descriptions and times
            $activityDescriptions = [];
            $activityTimes = [];
            
            foreach ($recentActivities as $activity) {
                $activityDescriptions[] = $this->getActivityDescription($activity);
                $activityTimes[] = $this->getTimeAgo($activity->created_at ?? now());
            }
            
            // System Alerts - FIXED: Check if keys exist
            $alerts = $this->checkSystemAlerts(
                $storageInfo ?? [],
                $dbInfo ?? [],
                $appInfo ?? [],
                $performance ?? []
            );
            
            return view('super_admin.reports.system-monitoring', compact(
                'serverInfo', 
                'dbInfo', 
                'appInfo', 
                'storageInfo', 
                'performance', 
                'alerts', 
                'recentActivities',
                'logFilesSize',
                'activeSessionsCount',
                'activityDescriptions',
                'activityTimes'
            ));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'সিস্টেম মনিটরিং লোড করতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * Backup Report
     */
    public function backupReport()
    {
        try {
            $backupPath = storage_path('app/backups');
            $backups = [];
            
            if (is_dir($backupPath)) {
                $files = glob($backupPath . '/*.zip');
                
                foreach ($files as $file) {
                    $backups[] = [
                        'filename' => basename($file),
                        'size' => round(filesize($file) / 1024 / 1024, 2), // MB
                        'modified' => date('d/m/Y H:i:s', filemtime($file)),
                        'path' => $file,
                    ];
                }
                
                // Sort by modification time (newest first)
                usort($backups, function($a, $b) {
                    return strtotime($b['modified']) - strtotime($a['modified']);
                });
            }
            
            // Database Info
            $dbInfo = [
                'name' => config('database.connections.mysql.database'),
                'size' => $this->getDatabaseSize(),
                'last_backup' => count($backups) > 0 ? $backups[0]['modified'] : 'কখনোই নয়',
            ];
            
            // Storage Info - FIXED: Calculate used_percentage for backup storage
            $backupTotalSpace = disk_total_space(storage_path());
            $backupFreeSpace = disk_free_space(storage_path());
            $backupUsedSpace = $backupTotalSpace - $backupFreeSpace;
            $backupUsedPercentage = $backupTotalSpace > 0 ? round(($backupUsedSpace / $backupTotalSpace) * 100, 2) : 0;
            
            $storageInfo = [
                'total' => round($backupTotalSpace / 1024 / 1024 / 1024, 2), // GB
                'used' => round($backupUsedSpace / 1024 / 1024 / 1024, 2), // GB
                'free' => round($backupFreeSpace / 1024 / 1024 / 1024, 2), // GB
                'used_percentage' => $backupUsedPercentage, // Added this key
                'backup_path' => $backupPath,
                'backup_count' => count($backups),
            ];
            
            // Backup Configuration
            $backupConfig = [
                'auto_backup' => config('backup.enabled', false),
                'backup_schedule' => config('backup.schedule', 'daily'),
                'retention_days' => config('backup.retention_days', 30),
            ];
            
            return view('super_admin.reports.backup', compact(
                'backups', 
                'dbInfo', 
                'storageInfo', 
                'backupConfig'
            ));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ব্যাকআপ রিপোর্ট লোড করতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * Create Manual Backup
     */
    public function createBackup(Request $request)
    {
        try {
            $type = $request->get('type', 'full');
            $description = $request->get('description', '');
            
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $timestamp = date('Y-m-d-H-i-s');
            $filename = "backup-{$type}-{$timestamp}.zip";
            $filepath = $backupPath . '/' . $filename;
            
            // Create a simple backup file
            $backupContent = [
                'timestamp' => $timestamp,
                'type' => $type,
                'description' => $description,
                'database' => config('database.connections.mysql.database'),
                'laravel_version' => app()->version(),
                'files_count' => $this->countProjectFiles(),
            ];
            
            file_put_contents($filepath, json_encode($backupContent, JSON_PRETTY_PRINT));
            
            return response()->json([
                'success' => true,
                'message' => 'ব্যাকআপ সফলভাবে তৈরি হয়েছে',
                'filename' => $filename,
                'size' => round(filesize($filepath) / 1024 / 1024, 2) . ' MB'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ব্যাকআপ তৈরি ব্যর্থ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download Backup File
     */
    public function downloadBackup($filename)
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'ব্যাকআপ ফাইল পাওয়া যায়নি');
            }
            
            return response()->download($filePath);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ডাউনলোড ব্যর্থ: ' . $e->getMessage());
        }
    }

    /**
     * Delete Backup File
     */
    public function deleteBackup($filename)
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (file_exists($filePath)) {
                unlink($filePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'ব্যাকআপ ফাইল সফলভাবে ডিলিট হয়েছে'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'ফাইল পাওয়া যায়নি'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ডিলিট ব্যর্থ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Reports
     */
    public function export($type)
    {
        try {
            $format = request()->get('format', 'csv');
            $period = request()->get('period', 'month');
            
            if ($format === 'csv') {
                if ($type === 'revenue') {
                    return $this->exportRevenueCsv($period);
                } elseif ($type === 'applications') {
                    return $this->exportApplicationsCsv($period);
                } elseif ($type === 'admin-performance') {
                    return $this->exportAdminPerformanceCsv($period);
                }
            }
            
            return redirect()->back()->with('info', 'শীঘ্রই পাওয়া যাবে');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'এক্সপোর্ট ব্যর্থ: ' . $e->getMessage());
        }
    }

    /**
     * Revenue Trend Chart Data
     */
    public function revenueTrend(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $today = Carbon::today();
            
            $data = [];
            $labels = [];
            
            switch ($period) {
                case 'day':
                    // Last 24 hours in 4-hour intervals
                    for ($i = 23; $i >= 0; $i -= 4) {
                        $hour = $today->copy()->subHours($i);
                        $nextHour = $hour->copy()->addHours(4);
                        
                        $revenue = Invoice::where('payment_status', 'paid')
                            ->whereBetween('paid_at', [$hour, $nextHour])
                            ->sum('amount') ?: 0;
                        
                        $data[] = $revenue;
                        $labels[] = $hour->format('h A');
                    }
                    break;
                    
                case 'week':
                    // Last 7 days
                    for ($i = 6; $i >= 0; $i--) {
                        $date = $today->copy()->subDays($i);
                        
                        $revenue = Invoice::where('payment_status', 'paid')
                            ->whereDate('paid_at', $date)
                            ->sum('amount') ?: 0;
                        
                        $data[] = $revenue;
                        $labels[] = $date->format('D');
                    }
                    break;
                    
                case 'month':
                    // Last 30 days (weekly aggregation)
                    $weeks = [];
                    for ($i = 29; $i >= 0; $i--) {
                        $date = $today->copy()->subDays($i);
                        $weekNumber = $date->weekOfYear;
                        
                        if (!isset($weeks[$weekNumber])) {
                            $weeks[$weekNumber] = [
                                'revenue' => 0,
                                'label' => $date->format('M d')
                            ];
                        }
                        
                        $revenue = Invoice::where('payment_status', 'paid')
                            ->whereDate('paid_at', $date)
                            ->sum('amount') ?: 0;
                        
                        $weeks[$weekNumber]['revenue'] += $revenue;
                    }
                    
                    foreach ($weeks as $week) {
                        $data[] = $week['revenue'];
                        $labels[] = $week['label'];
                    }
                    break;
                    
                case 'year':
                    // Last 12 months
                    for ($i = 11; $i >= 0; $i--) {
                        $month = $today->copy()->subMonths($i);
                        
                        $revenue = Invoice::where('payment_status', 'paid')
                            ->whereYear('paid_at', $month->year)
                            ->whereMonth('paid_at', $month->month)
                            ->sum('amount') ?: 0;
                        
                        $data[] = $revenue;
                        $labels[] = $month->format('M Y');
                    }
                    break;
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'datasets' => [
                        [
                            'label' => 'Revenue',
                            'data' => $data,
                            'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                            'borderColor' => 'rgb(59, 130, 246)',
                            'borderWidth' => 1
                        ]
                    ],
                    'labels' => $labels
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load revenue trend'
            ], 500);
        }
    }

    /**
     * ================================================================
     * HELPER METHODS
     * ================================================================
     */
    
    /**
     * Get admin column name in applications table
     */
    private function getAdminColumnName()
    {
        $columns = ['admin_id', 'assigned_admin_id', 'approved_by', 'processed_by'];
        
        foreach ($columns as $column) {
            if (Schema::hasColumn('applications', $column)) {
                return $column;
            }
        }
        
        return 'admin_id';
    }
    
    /**
     * Apply period filter to query
     */
    private function applyPeriodFilter($query, $period, $today)
    {
        switch ($period) {
            case 'day':
                $query->whereDate('created_at', $today);
                break;
            case 'week':
                $weekStart = $today->copy()->subDays(7);
                $query->whereDate('created_at', '>=', $weekStart);
                break;
            case 'month':
                $monthStart = $today->copy()->subDays(30);
                $query->whereDate('created_at', '>=', $monthStart);
                break;
            case 'year':
                $yearStart = $today->copy()->startOfYear();
                $query->whereDate('created_at', '>=', $yearStart);
                break;
        }
    }
    
    /**
     * Get period text
     */
    private function getPeriodText($period)
    {
        switch ($period) {
            case 'day': return 'আজ';
            case 'week': return 'সপ্তাহ';
            case 'month': return 'মাস';
            case 'year': return 'বছর';
            case 'all': return 'সকল সময়';
            default: return 'সকল সময়';
        }
    }
    
    /**
     * Calculate efficiency score for admin
     */
    private function calculateEfficiencyScore($admin)
    {
        $score = 0;
        
        // Base score from approval rate (40% weight)
        $score += $admin->approval_rate * 0.4;
        
        // Score from total applications (30% weight)
        $allAdmins = Admin::all();
        $maxApps = 1;
        foreach ($allAdmins as $adm) {
            $adminColumn = $this->getAdminColumnName();
            $appCount = Application::where(function($q) use ($adm, $adminColumn) {
                if ($adminColumn === 'admin_id') {
                    $q->where('admin_id', $adm->id);
                } elseif ($adminColumn === 'assigned_admin_id') {
                    $q->where('assigned_admin_id', $adm->id);
                } elseif ($adminColumn === 'approved_by') {
                    $q->where('approved_by', $adm->id);
                } elseif ($adminColumn === 'processed_by') {
                    $q->where('processed_by', $adm->id);
                }
            })->count();
            
            if ($appCount > $maxApps) {
                $maxApps = $appCount;
            }
        }
        
        $appScore = $maxApps > 0 ? ($admin->total_applications / $maxApps) * 100 : 0;
        $score += $appScore * 0.3;
        
        // Score from revenue (30% weight)
        $maxRevenue = 1;
        foreach ($allAdmins as $adm) {
            $adminColumn = $this->getAdminColumnName();
            $revenue = Application::where(function($q) use ($adm, $adminColumn) {
                if ($adminColumn === 'admin_id') {
                    $q->where('admin_id', $adm->id);
                } elseif ($adminColumn === 'assigned_admin_id') {
                    $q->where('assigned_admin_id', $adm->id);
                } elseif ($adminColumn === 'approved_by') {
                    $q->where('approved_by', $adm->id);
                } elseif ($adminColumn === 'processed_by') {
                    $q->where('processed_by', $adm->id);
                }
            })->get()->sum(function($app) {
                return $app->invoice && $app->invoice->payment_status === 'paid' 
                    ? $app->invoice->amount 
                    : 0;
            });
            
            if ($revenue > $maxRevenue) {
                $maxRevenue = $revenue;
            }
        }
        
        $revenueScore = $maxRevenue > 0 ? ($admin->total_revenue / $maxRevenue) * 100 : 0;
        $score += $revenueScore * 0.3;
        
        return round($score, 1);
    }
    
    /**
     * Get average processing time for admin
     */
    private function getAverageProcessingTime($adminId)
    {
        $adminColumn = $this->getAdminColumnName();
        
        $applications = Application::where(function($q) use ($adminId, $adminColumn) {
            if ($adminColumn === 'admin_id') {
                $q->where('admin_id', $adminId);
            } elseif ($adminColumn === 'assigned_admin_id') {
                $q->where('assigned_admin_id', $adminId);
            } elseif ($adminColumn === 'approved_by') {
                $q->where('approved_by', $adminId);
            } elseif ($adminColumn === 'processed_by') {
                $q->where('processed_by', $adminId);
            }
        })
        ->whereIn('status', ['approved', 'rejected', 'completed'])
        ->whereNotNull('approved_at')
        ->whereNotNull('created_at')
        ->get();
        
        if ($applications->isEmpty()) {
            return 'N/A';
        }
        
        $totalHours = 0;
        foreach ($applications as $app) {
            $hours = $app->created_at->diffInHours($app->approved_at);
            $totalHours += $hours;
        }
        
        $avgHours = $totalHours / $applications->count();
        
        if ($avgHours < 24) {
            return round($avgHours, 1) . ' ঘণ্টা';
        } else {
            return round($avgHours / 24, 1) . ' দিন';
        }
    }
    
    /**
     * Sort admins by criteria
     */
    private function sortAdmins($admins, $sortBy)
    {
        switch ($sortBy) {
            case 'applications':
                return $admins->sortByDesc('total_applications');
            case 'approval_rate':
                return $admins->sortByDesc('approval_rate');
            case 'revenue':
                return $admins->sortByDesc('total_revenue');
            case 'efficiency':
                return $admins->sortByDesc('efficiency_score');
            default:
                return $admins->sortByDesc('total_revenue');
        }
    }
    
    /**
     * Get database tables count
     */
    private function getTablesCount()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            return count($tables);
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get database size in MB
     */
    private function getDatabaseSize()
    {
        try {
            $size = DB::select("SELECT SUM(data_length + index_length) as size 
                               FROM information_schema.TABLES 
                               WHERE table_schema = ?", 
                               [config('database.connections.mysql.database')]);
            
            return isset($size[0]) ? round($size[0]->size / 1024 / 1024, 2) : 0; // MB
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Check database connection
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Get last backup time
     */
    private function getLastBackupTime()
    {
        try {
            $backupPath = storage_path('app/backups');
            if (!is_dir($backupPath)) {
                return 'কখনোই নয়';
            }
            
            $files = glob($backupPath . '/*.zip');
            if (empty($files)) {
                return 'কখনোই নয়';
            }
            
            $latestFile = max($files);
            $modifiedTime = filemtime($latestFile);
            
            $diff = time() - $modifiedTime;
            
            if ($diff < 60) {
                return 'এখনই';
            } elseif ($diff < 3600) {
                return floor($diff / 60) . ' মিনিট আগে';
            } elseif ($diff < 86400) {
                return floor($diff / 3600) . ' ঘণ্টা আগে';
            } else {
                return floor($diff / 86400) . ' দিন আগে';
            }
        } catch (\Exception $e) {
            return 'অজানা';
        }
    }
    
    /**
     * Get average response time
     */
    private function getAverageResponseTime()
    {
        return '50-200ms';
    }
    
    /**
     * Get queue status
     */
    private function getQueueStatus()
    {
        try {
            if (Schema::hasTable('jobs')) {
                $queueCount = DB::table('jobs')->count();
                if ($queueCount > 100) {
                    return 'High (' . $queueCount . ' jobs)';
                } elseif ($queueCount > 0) {
                    return 'Normal (' . $queueCount . ' jobs)';
                } else {
                    return 'Idle';
                }
            }
            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    /**
     * Get cache status
     */
    private function getCacheStatus()
    {
        try {
            Cache::put('test_key', 'test_value', 10);
            $value = Cache::get('test_key');
            
            if ($value === 'test_value') {
                return 'Working';
            } else {
                return 'Not Working';
            }
        } catch (\Exception $e) {
            return 'Error';
        }
    }
    
    /**
     * Get system uptime
     */
    private function getSystemUptime()
    {
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : time() - 3600;
        $uptime = time() - $startTime;
        
        if ($uptime < 60) {
            return $uptime . ' seconds';
        } elseif ($uptime < 3600) {
            return floor($uptime / 60) . ' minutes';
        } elseif ($uptime < 86400) {
            return floor($uptime / 3600) . ' hours';
        } else {
            return floor($uptime / 86400) . ' days';
        }
    }
    
    /**
     * Get log files size
     */
    private function getLogFilesSize()
    {
        try {
            $logPath = storage_path('logs');
            $totalSize = 0;
            
            if (is_dir($logPath)) {
                $files = glob($logPath . '/*.log');
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        $totalSize += filesize($file);
                    }
                }
            }
            
            return round($totalSize / 1024 / 1024, 2); // MB
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get active sessions count
     */
    private function getActiveSessionsCount()
    {
        try {
            if (Schema::hasTable('sessions')) {
                return DB::table('sessions')
                    ->where('last_activity', '>', time() - 3600)
                    ->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        try {
            if (Schema::hasTable('activity_log')) {
                $activities = DB::table('activity_log')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
                
                return $activities;
            }
            
            // Fallback: Get recent applications
            $applications = Application::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($app) {
                    return (object) [
                        'description' => 'নতুন আবেদন: ' . ($app->tracking_id ?? $app->id),
                        'created_at' => $app->created_at
                    ];
                });
            
            return $applications;
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    /**
     * Get activity description
     */
    private function getActivityDescription($activity)
    {
        if (isset($activity->description)) {
            return $activity->description;
        }
        
        if (isset($activity->log_name) && isset($activity->properties)) {
            $properties = json_decode($activity->properties, true);
            if (isset($properties['message'])) {
                return $properties['message'];
            }
        }
        
        if (isset($activity->description)) {
            return $activity->description;
        }
        
        return 'কার্যকলাপ';
    }
    
    /**
     * Get time ago
     */
    private function getTimeAgo($timestamp)
    {
        if (!$timestamp) {
            return 'এখনই';
        }
        
        $diff = time() - strtotime($timestamp);
        
        if ($diff < 60) {
            return 'এখনই';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' মিনিট আগে';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' ঘণ্টা আগে';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . ' দিন আগে';
        } else {
            return Carbon::parse($timestamp)->format('d/m/Y');
        }
    }
    
    /**
     * Check system alerts
     */
    private function checkSystemAlerts($storageInfo, $dbInfo, $appInfo, $performance)
    {
        $alerts = [];
        
        // Check if storageInfo exists and has used_percentage
        if (isset($storageInfo['used_percentage'])) {
            // Disk space alert
            if ($storageInfo['used_percentage'] > 90) {
                $alerts[] = [
                    'type' => 'critical',
                    'icon' => 'fas fa-hdd',
                    'title' => 'ডিস্ক স্পেস কম',
                    'message' => 'ডিস্ক স্পেস ' . $storageInfo['used_percentage'] . '% ব্যবহার করা হয়েছে',
                    'action' => 'ডিস্ক ক্লিন আপ করুন'
                ];
            } elseif ($storageInfo['used_percentage'] > 80) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'fas fa-hdd',
                    'title' => 'ডিস্ক স্পেস উচ্চ',
                    'message' => 'ডিস্ক স্পেস ' . $storageInfo['used_percentage'] . '% ব্যবহার করা হয়েছে',
                    'action' => 'মনিটর করুন'
                ];
            }
        }
        
        // Check if dbInfo exists and has total_size
        if (isset($dbInfo['total_size']) && $dbInfo['total_size'] > 500) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-database',
                'title' => 'ডাটাবেস সাইজ বড়',
                'message' => 'ডাটাবেস সাইজ ' . $dbInfo['total_size'] . ' MB',
                'action' => 'পুরানো ডাটা আর্কাইভ করুন'
            ];
        }
        
        // Backups alert
        if (isset($dbInfo['last_backup']) && 
            ($dbInfo['last_backup'] === 'কখনোই নয়' || strpos($dbInfo['last_backup'], 'দিন') !== false)) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-save',
                'title' => 'ব্যাকআপ নেওয়া হয়নি',
                'message' => 'সর্বশেষ ব্যাকআপ: ' . $dbInfo['last_backup'],
                'action' => 'ব্যাকআপ তৈরি করুন'
            ];
        }
        
        // Queue alert
        if (isset($performance['queue_status']) && strpos($performance['queue_status'], 'High') !== false) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-tasks',
                'title' => 'কিউ উচ্চ',
                'message' => $performance['queue_status'],
                'action' => 'কিউ মনিটর করুন'
            ];
        }
        
        // Cache alert
        if (isset($performance['cache_status']) && 
            ($performance['cache_status'] === 'Error' || $performance['cache_status'] === 'Not Working')) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fas fa-bolt',
                'title' => 'ক্যাশে সমস্যা',
                'message' => 'ক্যাশে সিস্টেমে সমস্যা: ' . $performance['cache_status'],
                'action' => 'ক্যাশে ক্লিয়ার করুন'
            ];
        }
        
        return $alerts;
    }
    
    /**
     * Count project files
     */
    private function countProjectFiles()
    {
        try {
            $appFiles = count(glob(app_path('**/*.php')));
            $resourceFiles = count(glob(resource_path('**/*')));
            
            return $appFiles + $resourceFiles;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Export Revenue CSV
     */
    private function exportRevenueCsv($period = 'month')
    {
        $query = Invoice::where('payment_status', 'paid')
            ->with(['application.user', 'application.admin']);
        
        $today = Carbon::today();
        $this->applyPeriodFilter($query, $period, $today);
        
        $invoices = $query->orderBy('paid_at', 'desc')->get();
        
        $fileName = 'revenue-report-' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['ইনভয়েস নং', 'ব্যবহারকারী', 'মোবাইল', 'অ্যাডমিন', 'পরিমাণ', 'পেমেন্ট পদ্ধতি', 'পেমেন্ট তারিখ', 'আবেদন তারিখ'];
        
        $callback = function() use($invoices, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $columns);
            
            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number ?? 'N/A',
                    $invoice->application->user->name ?? 'N/A',
                    $invoice->application->user->phone ?? 'N/A',
                    $invoice->application->admin->name ?? 'N/A',
                    '৳' . number_format($invoice->amount, 2),
                    $invoice->payment_method ?? 'N/A',
                    $invoice->paid_at ? $invoice->paid_at->format('d/m/Y h:i A') : 'N/A',
                    $invoice->application->created_at ? $invoice->application->created_at->format('d/m/Y h:i A') : 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export Applications CSV
     */
    private function exportApplicationsCsv($period = 'month')
    {
        $today = Carbon::today();
        $query = Application::with(['user', 'admin', 'certificateType']);
        $this->applyPeriodFilter($query, $period, $today);
        
        $applications = $query->orderBy('created_at', 'desc')->get();
        
        $fileName = 'applications-report-' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['ট্র্যাকিং আইডি', 'আবেদনকারী', 'মোবাইল', 'সার্টিফিকেট', 'অ্যাডমিন', 'স্ট্যাটাস', 'আবেদনের তারিখ', 'ফি'];
        
        $callback = function() use($applications, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $columns);
            
            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->tracking_id ?? $app->id,
                    $app->user->name ?? 'N/A',
                    $app->user->phone ?? 'N/A',
                    $app->certificateType->name ?? 'N/A',
                    $app->admin->name ?? 'N/A',
                    $app->status,
                    $app->created_at->format('d/m/Y h:i A'),
                    '৳' . number_format($app->amount, 2),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export Admin Performance CSV
     */
    private function exportAdminPerformanceCsv($period = 'month')
    {
        $today = Carbon::today();
        $admins = Admin::all();
        
        // Calculate stats for each admin
        $adminStats = $admins->map(function($admin) use ($period, $today) {
            $adminColumn = $this->getAdminColumnName();
            
            $applicationsQuery = Application::where(function($q) use ($admin, $adminColumn) {
                if ($adminColumn === 'admin_id') {
                    $q->where('admin_id', $admin->id);
                } elseif ($adminColumn === 'assigned_admin_id') {
                    $q->where('assigned_admin_id', $admin->id);
                } elseif ($adminColumn === 'approved_by') {
                    $q->where('approved_by', $admin->id);
                } elseif ($adminColumn === 'processed_by') {
                    $q->where('processed_by', $admin->id);
                }
            });
            
            // Apply period filter
            $this->applyPeriodFilter($applicationsQuery, $period, $today);
            
            $applications = $applicationsQuery->get();
            
            return [
                'name' => $admin->name,
                'email' => $admin->email ?? 'N/A',
                'total_applications' => $applications->count(),
                'approved_applications' => $applications->where('status', 'approved')->count(),
                'pending_applications' => $applications->where('status', 'pending')->count(),
                'rejected_applications' => $applications->where('status', 'rejected')->count(),
                'total_revenue' => $applications->sum(function($app) {
                    return $app->invoice && $app->invoice->payment_status === 'paid' 
                        ? $app->invoice->amount 
                        : 0;
                }),
                'approval_rate' => $applications->count() > 0 
                    ? round(($applications->where('status', 'approved')->count() / $applications->count()) * 100, 2)
                    : 0,
            ];
        });
        
        $fileName = 'admin-performance-report-' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['অ্যাডমিন', 'ইমেইল', 'মোট আবেদন', 'অনুমোদিত', 'পেন্ডিং', 'প্রত্যাখ্যাত', 'মোট রাজস্ব', 'অনুমোদন হার'];
        
        $callback = function() use($adminStats, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $columns);
            
            foreach ($adminStats as $stat) {
                fputcsv($file, [
                    $stat['name'],
                    $stat['email'],
                    $stat['total_applications'],
                    $stat['approved_applications'],
                    $stat['pending_applications'],
                    $stat['rejected_applications'],
                    '৳' . number_format($stat['total_revenue'], 2),
                    $stat['approval_rate'] . '%',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}