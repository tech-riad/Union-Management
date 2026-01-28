<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display all activity logs (super admin can see everyone's logs)
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_role')) {
            $query->where('user_role', $request->user_role);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        // Get statistics
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'top_users' => ActivityLog::select('user_id', 'user_name', DB::raw('count(*) as total'))
                ->groupBy('user_id', 'user_name')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get(),
            'module_counts' => ActivityLog::select('module', DB::raw('count(*) as total'))
                ->groupBy('module')
                ->orderBy('total', 'desc')
                ->get(),
            'recent_activities' => ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];

        // Get filter options
        $modules = ActivityLog::distinct()->pluck('module');
        $actions = ActivityLog::distinct()->pluck('action');
        $roles = ActivityLog::distinct()->pluck('user_role');

        return view('super_admin.activity_logs.index', compact('logs', 'stats', 'modules', 'actions', 'roles'));
    }

    /**
     * Show individual log details
     */
    public function show(ActivityLog $activityLog)
    {
        return view('super_admin.activity_logs.show', compact('activityLog'));
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        $logs = ActivityLog::query();

        if ($request->filled('date_from')) {
            $logs->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $logs->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $logs->orderBy('created_at', 'desc')->get();

        $filename = "activity_logs_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'ID', 'Date Time', 'User Name', 'Role', 'Action', 
                'Module', 'Description', 'IP Address', 'URL', 'Method'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('d-m-Y h:i:s A'),
                    $log->user_name,
                    $log->user_role,
                    $log->action,
                    $log->module,
                    $log->description,
                    $log->ip_address,
                    $log->url,
                    $log->method
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear old logs (older than 30 days)
     */
    public function clearOldLogs()
    {
        $cutoffDate = now()->subDays(30);
        $deleted = ActivityLog::where('created_at', '<', $cutoffDate)->delete();
        
        return redirect()->back()->with('success', "Deleted $deleted old logs (older than 30 days).");
    }

    /**
     * Get real-time activity statistics for dashboard
     */
    public function getDashboardStats()
    {
        $today = today();
        $yesterday = today()->subDay();
        
        $stats = [
            'today_count' => ActivityLog::whereDate('created_at', $today)->count(),
            'yesterday_count' => ActivityLog::whereDate('created_at', $yesterday)->count(),
            'top_activities' => ActivityLog::whereDate('created_at', $today)
                ->select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'hourly_activities' => ActivityLog::whereDate('created_at', $today)
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                ->groupBy(DB::raw('HOUR(created_at)'))
                ->orderBy('hour')
                ->get(),
            'recent_logins' => ActivityLog::where('action', 'LOGIN')
                ->whereDate('created_at', $today)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];
        
        return response()->json($stats);
    }
}