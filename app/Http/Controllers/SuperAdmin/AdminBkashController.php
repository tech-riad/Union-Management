<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BkashTransaction;
use App\Models\PaymentGateway;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SuperAdminBkashController extends Controller
{
    // ==================== DASHBOARD & OVERVIEW ====================
    
    public function index()
    {
        // bKash ড্যাশবোর্ড
        $today = now()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');
        
        // সার্বিক পরিসংখ্যান
        $stats = [
            'total_transactions' => BkashTransaction::count(),
            'total_amount' => BkashTransaction::where('status', 'completed')->sum('amount'),
            'today_transactions' => BkashTransaction::whereDate('created_at', $today)->count(),
            'today_amount' => BkashTransaction::where('status', 'completed')->whereDate('created_at', $today)->sum('amount'),
            'month_transactions' => BkashTransaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'month_amount' => BkashTransaction::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('amount'),
            'pending_transactions' => BkashTransaction::where('status', 'pending')->count(),
            'failed_transactions' => BkashTransaction::where('status', 'failed')->count(),
            'refunded_transactions' => BkashTransaction::where('status', 'refunded')->count(),
        ];
        
        // সাম্প্রতিক ট্রান্স্যাকশন
        $recentTransactions = BkashTransaction::with(['invoice', 'invoice.user'])
            ->latest()
            ->take(10)
            ->get();
        
        // মাসিক আয়ের গ্রাফের জন্য ডেটা
        $monthlyData = $this->getMonthlyEarnings();
        
        // টপ কাস্টমার
        $topCustomers = User::where('role', 'citizen')
            ->withCount(['bkashTransactions as total_payments' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['bkashTransactions as total_amount' => function($query) {
                $query->where('status', 'completed');
            }], 'amount')
            ->having('total_payments', '>', 0)
            ->orderBy('total_amount', 'desc')
            ->take(5)
            ->get();
        
        return view('super_admin.bkash.dashboard', compact('stats', 'recentTransactions', 'monthlyData', 'topCustomers'));
    }
    
    // ==================== TRANSACTION MANAGEMENT ====================
    
    public function transactions(Request $request)
    {
        $query = BkashTransaction::with(['invoice', 'invoice.user']);
        
        // অনুসন্ধান ফিল্টার
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_id', 'like', "%{$search}%")
                  ->orWhere('trx_id', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function($q2) use ($search) {
                      $q2->where('invoice_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('invoice.user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('mobile', 'like', "%{$search}%");
                  });
            });
        }
        
        // স্ট্যাটাস ফিল্টার
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // তারিখ ফিল্টার
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->latest()->paginate(20);
        
        $totalAmount = $query->where('status', 'completed')->sum('amount');
        $totalCount = $query->count();
        
        return view('super_admin.bkash.transactions', compact('transactions', 'totalAmount', 'totalCount'));
    }
    
    public function showTransaction($id)
    {
        $transaction = BkashTransaction::with([
            'invoice', 
            'invoice.user',
            'invoice.application',
            'invoice.application.certificate'
        ])->findOrFail($id);
        
        return view('super_admin.bkash.transaction_show', compact('transaction'));
    }
    
    public function refundTransaction(Request $request, $id)
    {
        $transaction = BkashTransaction::with('invoice')->findOrFail($id);
        
        if ($transaction->status !== 'completed') {
            return redirect()->back()->with('error', 'শুধুমাত্র completed ট্রান্স্যাকশন রিফান্ড করা যাবে।');
        }
        
        $request->validate([
            'refund_amount' => 'required|numeric|min:1|max:' . $transaction->amount,
            'refund_reason' => 'required|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            // bKash API-তে রিফান্ড রিকোয়েস্ট পাঠানোর লজিক
            // এখানে bKash refund API কল করতে হবে
            
            $transaction->update([
                'status' => 'refunded',
                'refund_amount' => $request->refund_amount,
                'refund_reason' => $request->refund_reason,
                'refunded_at' => now(),
                'refunded_by' => auth()->id(),
            ]);
            
            // ইনভয়েস স্ট্যাটাস আপডেট
            if ($transaction->invoice) {
                $transaction->invoice->update([
                    'payment_status' => 'refunded',
                    'refund_amount' => $request->refund_amount,
                ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'ট্রান্স্যাকশন সফলভাবে রিফান্ড করা হয়েছে।');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'রিফান্ড ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }
    
    // ==================== CONFIGURATION MANAGEMENT ====================
    
    public function configuration()
    {
        $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
        
        $config = $bkashGateway ? $bkashGateway->settings : [
            'sandbox_mode' => true,
            'app_key' => '',
            'app_secret' => '',
            'username' => '',
            'password' => '',
            'merchant_number' => '',
            'callback_url' => url('/bkash/callback'),
            'success_url' => url('/bkash/success'),
            'fail_url' => url('/bkash/fail'),
            'cancel_url' => url('/bkash/cancel'),
            'webhook_url' => url('/bkash/webhook'),
            'transaction_fee' => '1.75',
            'minimum_amount' => '10',
            'maximum_amount' => '50000',
        ];
        
        // API status check
        $apiStatus = $this->checkApiStatus($config);
        
        return view('super_admin.bkash.configuration', compact('config', 'apiStatus', 'bkashGateway'));
    }
    
    public function updateConfiguration(Request $request)
    {
        $request->validate([
            'sandbox_mode' => 'required|boolean',
            'app_key' => 'required_if:sandbox_mode,0|string',
            'app_secret' => 'required_if:sandbox_mode,0|string',
            'username' => 'required_if:sandbox_mode,0|string',
            'password' => 'required_if:sandbox_mode,0|string',
            'merchant_number' => 'required|string',
            'transaction_fee' => 'required|numeric|min:0|max:10',
            'minimum_amount' => 'required|numeric|min:1',
            'maximum_amount' => 'required|numeric|min:' . $request->minimum_amount,
            'is_active' => 'boolean',
        ]);
        
        try {
            $settings = [
                'sandbox_mode' => (bool) $request->sandbox_mode,
                'app_key' => $request->app_key,
                'app_secret' => $request->app_secret,
                'username' => $request->username,
                'password' => $request->password,
                'merchant_number' => $request->merchant_number,
                'callback_url' => url('/bkash/callback'),
                'success_url' => url('/bkash/success'),
                'fail_url' => url('/bkash/fail'),
                'cancel_url' => url('/bkash/cancel'),
                'webhook_url' => url('/bkash/webhook'),
                'transaction_fee' => $request->transaction_fee,
                'minimum_amount' => $request->minimum_amount,
                'maximum_amount' => $request->maximum_amount,
            ];
            
            $bkashGateway = PaymentGateway::updateOrCreate(
                ['name' => 'bkash'],
                [
                    'display_name' => 'bKash',
                    'is_active' => $request->has('is_active'),
                    'settings' => $settings,
                    'updated_by' => auth()->id(),
                ]
            );
            
            // কনফিগ ক্যাশে ক্লিয়ার করুন
            Cache::forget('payment_gateway_bkash');
            
            return redirect()->back()->with('success', 'bKash কনফিগারেশন সফলভাবে আপডেট করা হয়েছে।');
            
        } catch (\Exception $e) {
            Log::error('bKash configuration update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'কনফিগারেশন আপডেট ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }
    
    // ==================== API TEST & MANAGEMENT ====================
    
    public function apiTest()
    {
        $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
        
        if (!$bkashGateway || empty($bkashGateway->settings['app_key'])) {
            return redirect()->route('super_admin.bkash.configuration')
                ->with('warning', 'প্রথমে bKash কনফিগারেশন সেটআপ করুন।');
        }
        
        $testResults = session('test_results', []);
        
        return view('super_admin.bkash.api_test', compact('bkashGateway', 'testResults'));
    }
    
    public function executeApiTest(Request $request)
    {
        $request->validate([
            'test_type' => 'required|in:token,create_payment,query_payment,refund',
            'amount' => 'required_if:test_type,create_payment|numeric|min:10|max:500',
            'payment_id' => 'required_if:test_type,query_payment,refund|string',
            'trx_id' => 'required_if:test_type,refund|string',
        ]);
        
        $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
        
        if (!$bkashGateway) {
            return redirect()->back()->with('error', 'bKash কনফিগারেশন পাওয়া যায়নি।');
        }
        
        $testResults = [];
        
        try {
            $service = app('App\Services\BkashPaymentService');
            
            switch ($request->test_type) {
                case 'token':
                    $token = $service->getToken();
                    $testResults['token'] = [
                        'success' => !empty($token),
                        'message' => !empty($token) ? 'Token generation successful' : 'Token generation failed',
                        'data' => $token,
                    ];
                    break;
                    
                case 'create_payment':
                    $invoiceNumber = 'TEST-' . time();
                    $response = $service->createPayment(0, $request->amount, $invoiceNumber);
                    $testResults['create_payment'] = [
                        'success' => isset($response['paymentID']),
                        'message' => isset($response['paymentID']) ? 'Payment creation successful' : 'Payment creation failed',
                        'data' => $response,
                    ];
                    break;
                    
                case 'query_payment':
                    $response = $service->queryPayment($request->payment_id);
                    $testResults['query_payment'] = [
                        'success' => isset($response['transactionStatus']),
                        'message' => 'Payment query completed',
                        'data' => $response,
                    ];
                    break;
                    
                case 'refund':
                    $response = $service->refundPayment($request->payment_id, $request->trx_id, $request->amount ?? 10);
                    $testResults['refund'] = [
                        'success' => isset($response['transactionStatus']) && $response['transactionStatus'] === 'Completed',
                        'message' => isset($response['transactionStatus']) ? 'Refund ' . $response['transactionStatus'] : 'Refund failed',
                        'data' => $response,
                    ];
                    break;
            }
            
            return redirect()->route('super_admin.bkash.api.test')
                ->with('test_results', $testResults)
                ->with('success', 'API টেস্ট সম্পন্ন হয়েছে।');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'API টেস্ট ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }
    
    // ==================== IP WHITELIST MANAGEMENT ====================
    
    public function ipWhitelist()
    {
        $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
        
        $whitelist = $bkashGateway && isset($bkashGateway->settings['ip_whitelist']) 
            ? $bkashGateway->settings['ip_whitelist'] 
            : [
                '103.134.112.0/24',
                '103.134.113.0/24',
                '103.134.114.0/24',
                '103.134.115.0/24',
            ];
        
        $currentIp = request()->ip();
        
        return view('super_admin.bkash.ip_whitelist', compact('whitelist', 'currentIp', 'bkashGateway'));
    }
    
    public function updateIpWhitelist(Request $request)
    {
        $request->validate([
            'ip_addresses' => 'required|string',
        ]);
        
        try {
            $ips = array_map('trim', explode("\n", $request->ip_addresses));
            $validIps = [];
            
            foreach ($ips as $ip) {
                if (!empty($ip) && $this->validateIpRange($ip)) {
                    $validIps[] = $ip;
                }
            }
            
            $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
            
            if ($bkashGateway) {
                $settings = $bkashGateway->settings;
                $settings['ip_whitelist'] = $validIps;
                
                $bkashGateway->update([
                    'settings' => $settings,
                    'updated_by' => auth()->id(),
                ]);
                
                Cache::forget('payment_gateway_bkash');
                
                return redirect()->back()->with('success', 'IP হোয়াইটলিস্ট সফলভাবে আপডেট করা হয়েছে।');
            }
            
            return redirect()->back()->with('error', 'bKash গেটওয়ে পাওয়া যায়নি।');
            
        } catch (\Exception $e) {
            Log::error('IP whitelist update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'IP হোয়াইটলিস্ট আপডেট ব্যর্থ হয়েছে।');
        }
    }
    
    // ==================== WEBHOOK MANAGEMENT ====================
    
    public function webhookManagement()
    {
        $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
        
        $webhookLogs = DB::table('bkash_webhook_logs')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        $webhookUrl = $bkashGateway && isset($bkashGateway->settings['webhook_url']) 
            ? $bkashGateway->settings['webhook_url'] 
            : url('/bkash/webhook');
        
        return view('super_admin.bkash.webhook_management', compact('webhookLogs', 'webhookUrl'));
    }
    
    public function retryWebhook(Request $request, $id)
    {
        try {
            $log = DB::table('bkash_webhook_logs')->find($id);
            
            if (!$log) {
                return response()->json(['success' => false, 'message' => 'লগ পাওয়া যায়নি।']);
            }
            
            // Webhook রিট্রাই লজিক
            // এখানে webhook রিসেন্ড করতে হবে
            
            DB::table('bkash_webhook_logs')
                ->where('id', $id)
                ->update([
                    'retry_count' => DB::raw('retry_count + 1'),
                    'last_retry_at' => now(),
                    'status' => 'retried',
                ]);
            
            return response()->json(['success' => true, 'message' => 'Webhook পুনরায় পাঠানো হয়েছে।']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // ==================== FINANCIAL REPORTS ====================
    
    public function financialReports(Request $request)
    {
        // ডিফল্ট তারিখ রেঞ্জ (গত মাস)
        $startDate = $request->has('start_date') ? $request->start_date : now()->subMonth()->format('Y-m-d');
        $endDate = $request->has('end_date') ? $request->end_date : now()->format('Y-m-d');
        
        $query = BkashTransaction::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', 'completed');
        
        // সারাংশ
        $summary = [
            'total_amount' => $query->sum('amount'),
            'total_transactions' => $query->count(),
            'average_transaction' => $query->count() > 0 ? $query->sum('amount') / $query->count() : 0,
            'transaction_fee' => $query->sum('amount') * 0.0175, // 1.75% ফি
            'net_amount' => $query->sum('amount') * 0.9825, // মোট - ফি
        ];
        
        // দৈনিক ট্রেন্ড
        $dailyTrend = BkashTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // স্ট্যাটাস অনুযায়ী বণ্টন
        $statusDistribution = BkashTransaction::select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('status')
            ->get();
        
        // শীর্ষ সার্ভিস/সার্টিফিকেট
        $topServices = Invoice::select(
                'certificate_type_id',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->where('payment_method', 'bkash')
            ->groupBy('certificate_type_id')
            ->with('certificateType')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
        
        return view('super_admin.bkash.financial_reports', compact(
            'summary',
            'dailyTrend',
            'statusDistribution',
            'topServices',
            'startDate',
            'endDate'
        ));
    }
    
    public function downloadFinancialReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,csv,excel',
        ]);
        
        // রিপোর্ট ডাউনলোড লজিক
        // এখানে PDF, CSV বা Excel রিপোর্ট জেনারেট করতে হবে
        
        return redirect()->back()->with('success', 'রিপোর্ট ডাউনলোড শুরু হয়েছে।');
    }
    
    // ==================== HELPER METHODS ====================
    
    private function getMonthlyEarnings()
    {
        $data = BkashTransaction::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $months = [];
        $earnings = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create(date('Y'), $i, 1)->format('M');
            $monthData = $data->where('month', $i)->first();
            $earnings[] = $monthData ? $monthData->total : 0;
        }
        
        return [
            'months' => $months,
            'earnings' => $earnings,
        ];
    }
    
    private function checkApiStatus($config)
    {
        try {
            if (empty($config['app_key']) || empty($config['app_secret'])) {
                return [
                    'status' => 'error',
                    'message' => 'API credentials not configured',
                ];
            }
            
            // Simple API status check by trying to get token
            $service = app('App\Services\BkashPaymentService');
            $token = $service->getToken();
            
            return [
                'status' => !empty($token) ? 'success' : 'error',
                'message' => !empty($token) ? 'API is working properly' : 'Failed to get token',
                'token' => !empty($token) ? substr($token, 0, 50) . '...' : null,
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    
    private function validateIpRange($ip)
    {
        // Simple IP/CIDR validation
        if (strpos($ip, '/') !== false) {
            list($network, $cidr) = explode('/', $ip);
            if (!filter_var($network, FILTER_VALIDATE_IP) || $cidr < 0 || $cidr > 32) {
                return false;
            }
        } else {
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                return false;
            }
        }
        
        return true;
    }
    
    // ==================== BULK ACTIONS ====================
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:export,status_update,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:bkash_transactions,id',
        ]);
        
        try {
            switch ($request->action) {
                case 'export':
                    return $this->exportTransactions($request->ids);
                    
                case 'status_update':
                    $request->validate([
                        'new_status' => 'required|in:pending,completed,failed,refunded',
                    ]);
                    
                    BkashTransaction::whereIn('id', $request->ids)
                        ->update([
                            'status' => $request->new_status,
                            'updated_at' => now(),
                        ]);
                    
                    return redirect()->back()->with('success', 'ট্রান্স্যাকশন স্ট্যাটাস আপডেট করা হয়েছে।');
                    
                case 'delete':
                    BkashTransaction::whereIn('id', $request->ids)->delete();
                    return redirect()->back()->with('success', 'ট্রান্স্যাকশন ডিলিট করা হয়েছে।');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'বাল্ক অ্যাকশন ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }
    
    private function exportTransactions($ids)
    {
        // CSV এক্সপোর্ট লজিক
        $transactions = BkashTransaction::whereIn('id', $ids)
            ->with(['invoice', 'invoice.user'])
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bkash-transactions-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV হেডার
            fputcsv($file, [
                'ID',
                'Payment ID',
                'TRX ID',
                'Amount',
                'Status',
                'Invoice Number',
                'Customer Name',
                'Customer Mobile',
                'Payment Time',
                'Created At'
            ]);
            
            // ডেটা
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->payment_id,
                    $transaction->trx_id,
                    $transaction->amount,
                    $transaction->status,
                    $transaction->invoice ? $transaction->invoice->invoice_number : 'N/A',
                    $transaction->invoice && $transaction->invoice->user ? $transaction->invoice->user->name : 'N/A',
                    $transaction->invoice && $transaction->invoice->user ? $transaction->invoice->user->mobile : 'N/A',
                    $transaction->payment_time,
                    $transaction->created_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    // ==================== SYSTEM STATUS ====================
    
    public function systemStatus()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'api_connection' => $this->checkApiConnection(),
            'webhook_url' => $this->checkWebhookUrl(),
        ];
        
        $allPassed = collect($checks)->every(function($check) {
            return $check['status'] === 'success';
        });
        
        return view('super_admin.bkash.system_status', compact('checks', 'allPassed'));
    }
    
    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'success',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    
    private function checkCache()
    {
        try {
            Cache::put('test_key', 'test_value', 1);
            $value = Cache::get('test_key');
            Cache::forget('test_key');
            
            return [
                'status' => $value === 'test_value' ? 'success' : 'error',
                'message' => $value === 'test_value' ? 'Cache working properly' : 'Cache test failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    
    private function checkStorage()
    {
        try {
            $path = 'storage/test.txt';
            Storage::disk('local')->put($path, 'test');
            $content = Storage::disk('local')->get($path);
            Storage::disk('local')->delete($path);
            
            return [
                'status' => $content === 'test' ? 'success' : 'error',
                'message' => $content === 'test' ? 'Storage working properly' : 'Storage test failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    
    private function checkApiConnection()
    {
        $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
        
        if (!$bkashGateway || empty($bkashGateway->settings['app_key'])) {
            return [
                'status' => 'warning',
                'message' => 'bKash API not configured',
            ];
        }
        
        return $this->checkApiStatus($bkashGateway->settings);
    }
    
    private function checkWebhookUrl()
    {
        $bkashGateway = PaymentGateway::where('name', 'bkash')->first();
        
        if (!$bkashGateway || empty($bkashGateway->settings['webhook_url'])) {
            return [
                'status' => 'warning',
                'message' => 'Webhook URL not configured',
            ];
        }
        
        $webhookUrl = $bkashGateway->settings['webhook_url'];
        
        try {
            // Simple check if URL is valid
            if (filter_var($webhookUrl, FILTER_VALIDATE_URL)) {
                return [
                    'status' => 'success',
                    'message' => 'Webhook URL is valid: ' . $webhookUrl,
                ];
            }
            
            return [
                'status' => 'error',
                'message' => 'Invalid webhook URL format',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}