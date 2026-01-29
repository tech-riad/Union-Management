<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CitizenRegisterController;
use App\Http\Controllers\Citizen\ProfileController;
use App\Http\Controllers\Citizen\CertificateApplicationController;
use App\Http\Controllers\Citizen\InvoiceController;
use App\Http\Controllers\Citizen\PaymentController;
use App\Http\Controllers\Admin\ApplicationManageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\SuperAdmin\CertificateController;
use App\Http\Controllers\SuperAdmin\ApplicationManageController as SuperAdminApplicationManageController;
use App\Http\Controllers\SuperAdmin\UnionSettingController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\Certificate\PdfController;
use App\Http\Controllers\CertificateController as CertificateVerificationController;
use App\Http\Controllers\Payment\BkashController;
use App\Http\Controllers\SuperAdmin\SuperAdminBkashController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\SuperAdmin\SuperReportController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\SystemUpdateController;
use App\Http\Controllers\BkashPaymentController;
use App\Http\Controllers\BkashTokenizePaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================= PUBLIC ROUTES =================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ================= AUTHENTICATION ROUTES =================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [CitizenRegisterController::class, 'showRegisterForm'])
    ->middleware('guest')
    ->name('register');
Route::post('/register', [CitizenRegisterController::class, 'register'])
    ->middleware('guest')
    ->name('register.submit');

// ================= CERTIFICATE VERIFICATION SYSTEM =================
Route::get('/verify', [CertificateVerificationController::class, 'verifyForm'])
    ->name('certificate.verify.form');
Route::post('/verify', [CertificateVerificationController::class, 'processVerify'])
    ->name('certificate.verify.process');
Route::get('/verify/{cid}', [CertificateVerificationController::class, 'verify'])
    ->name('certificate.verify');

Route::prefix('certificate')->name('certificate.')->group(function () {
    Route::get('/verify', [CertificateVerificationController::class, 'verifyForm'])
        ->name('verify.form.alias');
    Route::post('/verify', [CertificateVerificationController::class, 'processVerify'])
        ->name('verify.process.alias');
    Route::get('/verify/{cid}', [CertificateVerificationController::class, 'verify'])
        ->name('verify.alias');
    Route::get('/{id}/pdf', [CertificateVerificationController::class, 'generatePDF'])
        ->name('pdf');
});

Route::get('/api/certificate/verify/{certificateNumber}', [CertificateVerificationController::class, 'verifyAPI'])
    ->name('certificate.verify.api');

// ================= BKASH PUBLIC ROUTES =================
Route::prefix('bkash')->name('bkash.')->group(function () {
    Route::post('/citizen/bkash/pay/{invoice}',[PaymentController::class, 'initiatePayment'])->name('citizen.bkash.create.invoice');
    Route::post('/citizen/bkash/callback', [PaymentController::class, 'bKashCallback'])->name('citizen.bkash.callback');
    Route::post('/create-payment', [BkashController::class, 'createPayment'])->name('create');
    Route::post('/execute-payment', [BkashController::class, 'executePayment'])->name('execute');
    Route::get('/success', [BkashController::class, 'success'])->name('success');
    Route::get('/fail', [BkashController::class, 'fail'])->name('fail');
    Route::get('/cancel', [BkashController::class, 'cancel'])->name('cancel');
    Route::post('/webhook', [BkashController::class, 'webhook'])->name('webhook');
});

// ================= PUBLIC TEST ROUTES =================
Route::get('/test-simple', function () {
    return "Route test - Working!";
});

Route::get('/test-pdf/{id}', [CertificateVerificationController::class, 'generatePDF'])
    ->name('test.pdf');
Route::get('/test-qr/{text?}', [CertificateVerificationController::class, 'testQR'])
    ->name('test.qr');
Route::get('/test-tcpdf', [InvoiceController::class, 'testTCPDF'])->name('test.tcpdf');
Route::get('/simple-test', [InvoiceController::class, 'simpleTest'])->name('test.simple');
Route::get('/test-pdf', [InvoiceController::class, 'test'])->name('test.pdf');
Route::get('/system-check', [InvoiceController::class, 'systemCheck'])->name('system.check');
Route::get('/check-fonts', [InvoiceController::class, 'checkFonts'])->name('check.fonts');
Route::get('/fix-fonts', [InvoiceController::class, 'fixFontDirectory'])->name('test.fonts');
Route::get('/install-font', [InvoiceController::class, 'installBanglaFont'])->name('test.install.font');
Route::get('/test-union', function() {
    return view('test-union');
});
Route::get('/test-bkash-direct', function () {
    return view('test.bkash-direct');
})->name('test.bkash.direct');

// ================= HOME ROUTE =================
Route::get('/home', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return redirect()->route('super_admin.dashboard');
        } elseif (in_array($user->role, ['admin', 'secretary', 'citizen'])) {
            return redirect()->route('admin.dashboard');
        }
    }
    return redirect('/login');
})->name('home');

// ================= AUTHENTICATED ROUTES =================
Route::middleware(['auth'])->group(function () {

    // ================= DASHBOARDS =================
    // সুপার অ্যাডমিন ড্যাশবোর্ড - DashboardController ব্যবহার করুন
    Route::get('/super-admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('super_admin.dashboard');

    // অ্যাডমিন ড্যাশবোর্ড
    Route::get('/admin/dashboard', fn () => view('dashboards.admin'))
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // সেক্রেটারি ড্যাশবোর্ড
    Route::get('/secretary/dashboard', fn () => view('dashboards.secretary'))
        ->middleware('role:secretary')
        ->name('secretary.dashboard');

    // সিটিজেন ড্যাশবোর্ড
    Route::get('/citizen/dashboard', fn () => view('dashboards.citizen'))
        ->middleware('role:citizen')
        ->name('citizen.dashboard');

    // ======================================================================
    // ============================ CITIZEN ROUTES ==========================
    // ======================================================================
    Route::middleware(['role:citizen'])
        ->prefix('citizen')
        ->name('citizen.')
        ->group(function () {

        // -------- PROFILE --------
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

        // -------- CERTIFICATE APPLICATIONS --------
        Route::get('/certificates', [CertificateApplicationController::class, 'index'])->name('certificates.index');
        Route::get('/applications', [CertificateApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [CertificateApplicationController::class, 'show'])->name('applications.show');

        // সার্টিফিকেট টাইপের জন্য আবেদন ফর্ম - CertificateType মডেল ব্যবহার করুন
        Route::get('/certificates/{certificateType}/apply', [CertificateApplicationController::class, 'apply'])
            ->name('applications.apply');
        Route::post('/certificates/{certificateType}/apply', [CertificateApplicationController::class, 'store'])
            ->name('applications.store');

        Route::get('/applications/{application}/certificate-pdf',
            [CertificateApplicationController::class, 'certificatePdf']
        )->name('applications.certificate.pdf');

        // -------- INVOICES --------
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('pdf');
            Route::get('/{invoice}/view', [InvoiceController::class, 'view'])->name('view');
            Route::post('/{invoice}/pay', [InvoiceController::class, 'pay'])->name('pay');
        });

        // -------- PAYMENTS --------
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/bkash/payment', [App\Http\Controllers\BkashTokenizePaymentController::class,'index']);
            Route::post('/bkash/create-payment', [App\Http\Controllers\BkashTokenizePaymentController::class,'createPayment'])->name('bkash-create-payment');
            // Route::post('/bkash/callback', [App\Http\Controllers\BkashTokenizePaymentController::class,'callBack'])->name('bkash-callBack');



            Route::get('/{invoice}', [PaymentController::class, 'showPaymentPage'])->name('show');
            Route::post('/{invoice}/initiate', [PaymentController::class, 'initiatePayment'])->name('initiate');
            Route::post('/{invoice}/bkash', [PaymentController::class, 'directBkashPayment'])->name('bkash.direct');
            Route::post('/{invoice}/confirm-manual', [PaymentController::class, 'confirmManualPayment'])->name('confirm.manual');
            Route::get('/success', [PaymentController::class, 'paymentSuccess'])->name('success');
            Route::get('/failed', [PaymentController::class, 'paymentFailed'])->name('failed');
            Route::get('/{invoice}/success', [PaymentController::class, 'paymentSuccess'])->name('success.invoice');
            Route::get('/{invoice}/failed', [PaymentController::class, 'paymentFailed'])->name('failed.invoice');
            Route::get('/{invoice}/check-status', [PaymentController::class, 'checkStatus'])->name('check.status');

            // পেমেন্ট হিস্টোরি
            Route::get('/payment-history', [PaymentController::class, 'paymentHistory'])->name('history');

            // বিকাশ পেমেন্ট ক্রিয়েট রুট
            Route::post('/{invoice}/create', [PaymentController::class, 'createBkashPayment'])->name('create.invoice');
        });
    });

    // ======================================================================
    // ======================= ADMIN/SECRETARY ROUTES =======================
    // ======================================================================
    Route::middleware(['role:admin,secretary'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        // -------- DASHBOARD --------
        Route::get('/dashboard', fn () => view('dashboards.admin'))->name('dashboard');

        // -------- REPORTS --------
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/revenue', [ReportController::class, 'revenueReport'])->name('revenue');
            Route::get('/revenue/data', [ReportController::class, 'getRevenueData'])->name('revenue.data');
            Route::get('/applications', [ReportController::class, 'applicationReport'])->name('applications');
            Route::get('/applications/data', [ReportController::class, 'getApplicationData'])->name('applications.data');
            Route::get('/monthly-data', [ReportController::class, 'monthlyData'])->name('monthly.data');
            Route::get('/dashboard-stats', [ReportController::class, 'getDashboardStats'])->name('dashboard.stats');
            Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
        });

        // -------- APPLICATIONS MANAGEMENT --------
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationManageController::class, 'index'])->name('index');
            Route::get('/{application}', [ApplicationManageController::class, 'show'])->name('show');
            Route::post('/{application}/approve', [ApplicationManageController::class, 'approve'])->name('approve');
            Route::post('/{application}/reject', [ApplicationManageController::class, 'reject'])->name('reject');
            Route::post('/{application}/old-approve', [ApplicationManageController::class, 'oldApprove'])->name('old.approve');
            Route::post('/{application}/old-reject', [ApplicationManageController::class, 'oldReject'])->name('old.reject');
            Route::put('/{application}/status', [ApplicationManageController::class, 'updateStatus'])->name('status.update');
            Route::post('/{application}/payment', [ApplicationManageController::class, 'updatePayment'])->name('payment.update');
            Route::post('/bulk-action', [ApplicationManageController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/{application}/certificate-pdf', [CertificateApplicationController::class, 'certificatePdf'])->name('certificate.pdf');
        });

        // -------- USER MANAGEMENT --------
        Route::prefix('users')->name('users.')->group(function() {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');

            Route::middleware(['check.role:admin,super_admin'])->group(function() {
                Route::post('/', [AdminUserController::class, 'store'])->name('store');
                Route::put('/{user}/status', [AdminUserController::class, 'updateStatus'])->name('status');
                Route::post('/bulk-action', [AdminUserController::class, 'bulkAction'])->name('bulk-action');
                Route::get('/export', [AdminUserController::class, 'export'])->name('export');
            });

            Route::middleware(['check.role:super_admin'])->group(function() {
                Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
            });
        });

        // -------- BKASH MANAGEMENT --------
        Route::prefix('bkash')->name('bkash.')->group(function () {
            Route::get('/transactions', [SuperAdminBkashController::class, 'transactions'])->name('transactions.index');
            Route::get('/transactions/{transaction}', [SuperAdminBkashController::class, 'showTransaction'])->name('transactions.show');
            Route::post('/transactions/{transaction}/refund', [SuperAdminBkashController::class, 'refundTransaction'])->name('transactions.refund');
            Route::post('/toggle-status', [SuperAdminBkashController::class, 'toggleStatus'])->name('toggle.status');
            Route::get('/settings', [SuperAdminBkashController::class, 'settings'])->name('settings');
            Route::post('/settings/update', [SuperAdminBkashController::class, 'updateSettings'])->name('settings.update');
            Route::get('/reports', [SuperAdminBkashController::class, 'reports'])->name('reports');
            Route::get('/export', [SuperAdminBkashController::class, 'exportTransactions'])->name('export');
        });

        // -------- PAYMENT GATEWAY MANAGEMENT --------
        Route::prefix('payment-gateways')->name('payment.gateways.')->group(function () {
            Route::get('/', [SuperAdminBkashController::class, 'gatewayList'])->name('index');
            Route::get('/{gateway}/edit', [SuperAdminBkashController::class, 'gatewayEdit'])->name('edit');
            Route::put('/{gateway}/update', [SuperAdminBkashController::class, 'gatewayUpdate'])->name('update');
            Route::post('/{gateway}/toggle', [SuperAdminBkashController::class, 'gatewayToggle'])->name('toggle');
        });

        // -------- ADMIN PROFILE ROUTES --------
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', function () {
                return view('admin.profile.show');
            })->name('index');

            Route::get('/edit', function () {
                return view('admin.profile.edit');
            })->name('edit');

            Route::put('/update', function (\Illuminate\Http\Request $request) {
                // Profile update logic
            })->name('update');

            Route::post('/upload-image', function (\Illuminate\Http\Request $request) {
                // Upload image logic
            })->name('upload-image');

            Route::post('/remove-image', function () {
                // Remove image logic
            })->name('remove-image');
        });

        // -------- ADMIN PASSWORD CHANGE ROUTES --------
        Route::prefix('password')->name('password.')->group(function () {
            Route::get('/change', function () {
                return view('admin.profile.change-password');
            })->name('change');

            Route::put('/update', function (\Illuminate\Http\Request $request) {
                // Password update logic
            })->name('update');
        });

        // -------- SETTINGS ROUTES --------
        Route::get('/settings', function () {
            $user = auth()->user();
            if ($user->role === 'admin' || $user->role === 'super_admin') {
                return view('admin.settings');
            }
            abort(403, 'Unauthorized access.');
        })->name('settings');

        Route::put('/settings', function (\Illuminate\Http\Request $request) {
            // Settings update logic
        })->name('settings.update');
    });

    // ======================================================================
    // ========================= SUPER ADMIN ROUTES =========================
    // ======================================================================
    Route::middleware(['role:super_admin'])
        ->prefix('super-admin')
        ->name('super_admin.')
        ->group(function () {

        // -------- DASHBOARD --------
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // -------- REPORTS --------
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [SuperReportController::class, 'index'])->name('index');
            Route::get('/dashboard-stats', [SuperReportController::class, 'getDashboardStats'])->name('dashboard-stats');
            Route::get('/revenue', [SuperReportController::class, 'revenueReport'])->name('revenue');
            Route::get('/applications', [SuperReportController::class, 'applicationReport'])->name('applications');
            Route::get('/admin-performance', [SuperReportController::class, 'adminPerformance'])->name('admin-performance');
            Route::get('/system-monitoring', [SuperReportController::class, 'systemMonitoring'])->name('system-monitoring');
            Route::get('/backup', [SuperReportController::class, 'backupReport'])->name('backup');

            // Backup operations
            Route::post('/create-backup', [SuperReportController::class, 'createBackup'])->name('create-backup');
            Route::post('/upload-backup', [SuperReportController::class, 'uploadBackup'])->name('upload-backup');
            Route::post('/download-multiple', [SuperReportController::class, 'downloadMultiple'])->name('download-multiple');
            Route::delete('/delete-multiple', [SuperReportController::class, 'deleteMultiple'])->name('delete-multiple');
            Route::post('/restore-backup', [SuperReportController::class, 'restoreBackup'])->name('restore-backup');
            Route::get('/download-backup/{filename}', [SuperReportController::class, 'downloadBackup'])->name('download-backup');
            Route::delete('/delete-backup/{filename}', [SuperReportController::class, 'deleteBackup'])->name('delete-backup');

            // Export
            Route::get('/export/{type}', [SuperReportController::class, 'export'])->name('export');

            // Additional Reports
            Route::get('/revenue-trend', [SuperReportController::class, 'revenueTrend'])->name('revenue-trend');
            Route::get('/user-analysis', [SuperReportController::class, 'userAnalysis'])->name('user-analysis');
            Route::get('/geographical', [SuperReportController::class, 'geographicalReport'])->name('geographical');
            Route::get('/financial-analytics', [SuperReportController::class, 'financialAnalytics'])->name('financial-analytics');
            Route::get('/audit-trail', [SuperReportController::class, 'auditTrail'])->name('audit-trail');
        });

        // ================= SYSTEM UPDATE ROUTES =================
        Route::prefix('system-update')->name('system_update.')->group(function () {
            // System Update Page
            Route::get('/', [SystemUpdateController::class, 'index'])->name('index');

            // AJAX endpoints for system update
            Route::post('/check', [SystemUpdateController::class, 'check'])->name('check');
            Route::post('/download', [SystemUpdateController::class, 'download'])->name('download');
            Route::post('/install', [SystemUpdateController::class, 'install'])->name('install');
            Route::get('/progress/{id}', [SystemUpdateController::class, 'progress'])->name('progress');
            Route::get('/info', [SystemUpdateController::class, 'info'])->name('info');
            Route::post('/backup', [SystemUpdateController::class, 'backup'])->name('backup');
            Route::post('/upload', [SystemUpdateController::class, 'upload'])->name('upload');
            Route::post('/rollback', [SystemUpdateController::class, 'rollback'])->name('rollback');
            Route::get('/history', [SystemUpdateController::class, 'history'])->name('history');
            Route::post('/test-connection', [SystemUpdateController::class, 'testConnection'])->name('test_connection');
        });

        // -------- ACTIVITY LOGS --------
        Route::prefix('activity-logs')->name('activity_logs.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
            Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
            Route::delete('/clear', [ActivityLogController::class, 'clearOldLogs'])->name('clear');
        });

        // -------- SETTINGS --------
        Route::prefix('settings')->name('settings.')->group(function () {
            // Union Settings
            Route::get('/union', [UnionSettingController::class, 'index'])->name('union');
            Route::put('/union/update', [UnionSettingController::class, 'update'])->name('union.update');
            Route::post('/union/delete-image', [UnionSettingController::class, 'deleteImage'])->name('union.delete-image');
            Route::get('/union/reset', [UnionSettingController::class, 'reset'])->name('union.reset');

            // Backup Settings
            Route::get('/backup', [UnionSettingController::class, 'backupSettings'])->name('backup');
            Route::put('/backup/update', [UnionSettingController::class, 'updateBackupSettings'])->name('update-backup');
            Route::post('/backup/test', [UnionSettingController::class, 'testBackup'])->name('test-backup');

            // Additional Backup Routes
            Route::get('/backup/logs', [UnionSettingController::class, 'getBackupLogs'])->name('backup.logs');
            Route::post('/backup/cleanup', [UnionSettingController::class, 'runBackupCleanup'])->name('backup.cleanup');
            Route::get('/backup/health', [UnionSettingController::class, 'getSystemHealth'])->name('backup.health');
        });

        // -------- APPLICATIONS MANAGEMENT --------
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [SuperAdminApplicationManageController::class, 'index'])->name('index');
            Route::get('/search', [SuperAdminApplicationManageController::class, 'search'])->name('search');
            Route::get('/statistics', [SuperAdminApplicationManageController::class, 'statistics'])->name('statistics');
            Route::get('/{application}', [SuperAdminApplicationManageController::class, 'show'])->name('show');
            Route::put('/{application}', [SuperAdminApplicationManageController::class, 'update'])->name('update');
            Route::delete('/{application}', [SuperAdminApplicationManageController::class, 'destroy'])->name('destroy');
            Route::post('/{application}/approve', [SuperAdminApplicationManageController::class, 'approve'])->name('approve');
            Route::post('/{application}/reject', [SuperAdminApplicationManageController::class, 'reject'])->name('reject');
            Route::post('/{application}/payment', [SuperAdminApplicationManageController::class, 'updatePayment'])->name('payment');
            Route::post('/bulk-action', [SuperAdminApplicationManageController::class, 'bulkAction'])->name('bulk.action');
            Route::get('/{application}/certificate-pdf-qr', [CertificateVerificationController::class, 'generatePDF'])->name('certificate.pdf.qr');
            Route::post('/bulk-download', [CertificateVerificationController::class, 'bulkGeneratePDF'])->name('bulk.download');
        });

        // ================= USER MANAGEMENT ROUTES =================
        Route::prefix('users')->name('users.')->group(function() {
            // প্রধান ইউজার লিস্ট (সব টাইপ)
            Route::get('/', [SuperAdminUserController::class, 'index'])->name('index');

            Route::get('/export', [SuperAdminUserController::class, 'export'])->name('export');

            // আলাদা ইউজার টাইপ লিস্ট
            Route::prefix('admins')->name('admins.')->group(function() {
                Route::get('/', [SuperAdminUserController::class, 'adminList'])->name('index');
                Route::get('/create', [SuperAdminUserController::class, 'createAdmin'])->name('create');
                Route::post('/store', [SuperAdminUserController::class, 'storeAdmin'])->name('store');
                Route::get('/{user}/edit', [SuperAdminUserController::class, 'editAdmin'])->name('edit');  // এখানে {user}
                Route::put('/{user}', [SuperAdminUserController::class, 'updateAdmin'])->name('update');  // এখানে {user}
            });

            Route::prefix('secretaries')->name('secretaries.')->group(function() {
                Route::get('/', [SuperAdminUserController::class, 'secretaryList'])->name('index');
                Route::get('/create', [SuperAdminUserController::class, 'createSecretary'])->name('create');
                Route::post('/store', [SuperAdminUserController::class, 'storeSecretary'])->name('store');
                Route::get('/{user}/edit', [SuperAdminUserController::class, 'editSecretary'])->name('edit');  // এখানে {user}
                Route::put('/{user}', [SuperAdminUserController::class, 'updateSecretary'])->name('update');  // এখানে {user}
            });

            Route::prefix('citizens')->name('citizens.')->group(function() {
                Route::get('/', [SuperAdminUserController::class, 'citizenList'])->name('index');
                Route::get('/create', [SuperAdminUserController::class, 'createCitizen'])->name('create');
                Route::post('/store', [SuperAdminUserController::class, 'storeCitizen'])->name('store');
                Route::get('/{user}/edit', [SuperAdminUserController::class, 'editCitizen'])->name('edit');  // এখানে {user}
                Route::put('/{user}', [SuperAdminUserController::class, 'updateCitizen'])->name('update');  // এখানে {user}
            });

            // কমন ইউজার অপারেশন
            Route::get('/create', [SuperAdminUserController::class, 'create'])->name('create');
            Route::post('/', [SuperAdminUserController::class, 'store'])->name('store');
            Route::get('/{user}', [SuperAdminUserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [SuperAdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [SuperAdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [SuperAdminUserController::class, 'destroy'])->name('destroy');

            // Status Update Route
            Route::put('/{user}/status', [SuperAdminUserController::class, 'updateStatus'])->name('update-status');

            Route::post('/bulk-action', [SuperAdminUserController::class, 'bulkAction'])->name('bulk.action');
        });

        // ================= SUPER ADMIN PROFILE ROUTES =================
        Route::prefix('profile')->name('profile.')->group(function () {
            // Profile Show (Use existing admin edit route for now)
            Route::get('/', function () {
                return redirect()->route('super_admin.users.admins.edit', auth()->user());
            })->name('show');

            // Profile Password Change Page
            Route::get('/password', function () {
                return view('super_admin.profile.password');
            })->name('password');

            // Update Password
            Route::post('/update-password', function (\Illuminate\Http\Request $request) {
                $request->validate([
                    'current_password' => 'required',
                    'password' => 'required|confirmed|min:8',
                ]);

                if (!\Hash::check($request->current_password, auth()->user()->password)) {
                    return back()->withErrors(['current_password' => 'Current password is incorrect']);
                }

                auth()->user()->update([
                    'password' => \Hash::make($request->password)
                ]);

                return back()->with('success', 'Password updated successfully');
            })->name('update-password');
        });

        // -------- BKASH MANAGEMENT --------
        Route::prefix('bkash')->name('bkash.')->group(function () {
            Route::get('/', [SuperAdminBkashController::class, 'index'])->name('dashboard');
            Route::get('/transactions', [SuperAdminBkashController::class, 'transactions'])->name('transactions');
            Route::get('/configuration', [SuperAdminBkashController::class, 'configuration'])->name('configuration');
            Route::post('/configuration/update', [SuperAdminBkashController::class, 'updateConfiguration'])->name('configuration.update');
            Route::get('/financial-reports', [SuperAdminBkashController::class, 'financialReports'])->name('financial.reports');
            Route::get('/financial-reports/download', [SuperAdminBkashController::class, 'downloadFinancialReport'])->name('financial.reports.download');

            // Additional bKash routes
            Route::get('/api-test', [SuperAdminBkashController::class, 'apiTest'])->name('api.test');
            Route::post('/api-test/execute', [SuperAdminBkashController::class, 'executeApiTest'])->name('api.test.execute');
            Route::get('/ip-whitelist', [SuperAdminBkashController::class, 'ipWhitelist'])->name('ip.whitelist');
            Route::post('/ip-whitelist/update', [SuperAdminBkashController::class, 'updateIpWhitelist'])->name('ip.whitelist.update');
            Route::get('/webhook-management', [SuperAdminBkashController::class, 'webhookManagement'])->name('webhook.management');
            Route::get('/system-status', [SuperAdminBkashController::class, 'systemStatus'])->name('system.status');
        });

        // -------- CERTIFICATE TYPES MANAGEMENT --------
        Route::prefix('certificates')->name('certificates.')->group(function () {
            Route::get('/', [CertificateController::class, 'index'])->name('index');
            Route::get('/create', [CertificateController::class, 'create'])->name('create');
            Route::post('/', [CertificateController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CertificateController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CertificateController::class, 'update'])->name('update');
            Route::delete('/{id}', [CertificateController::class, 'destroy'])->name('destroy');
        });

        // -------- PDF GENERATION --------
        Route::prefix('certificates-pdf')->name('certificates.pdf.')->group(function () {
            Route::get('/{application}', [PdfController::class, 'generateCertificate'])->name('generate');
            Route::get('/{application}/download', [PdfController::class, 'downloadCertificate'])->name('download');
            Route::get('/preview', [PdfController::class, 'previewCertificate'])->name('preview');
        });

        // -------- VERIFICATION SYSTEM MANAGEMENT --------
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/logs', function () {
                return view('super_admin.verification.logs');
            })->name('logs');

            Route::get('/statistics', function () {
                return view('super_admin.verification.statistics');
            })->name('statistics');
        });
    });

    // ================= PDF ROUTES (FOR ALL USERS) =================
    Route::get('/certificates/{application}/pdf', [PdfController::class, 'generateCertificate'])
        ->name('certificates.pdf');
    Route::get('/certificates/{application}/download', [PdfController::class, 'downloadCertificate'])
        ->name('certificates.download');
    Route::get('/certificates/{application}/pdf-qr', [CertificateVerificationController::class, 'generatePDF'])
        ->name('certificates.pdf.qr');

    // ================= PAYMENT GATEWAY CALLBACK ROUTES =================
    Route::prefix('payment')->group(function () {
        Route::post('/bkash/callback', [PaymentController::class, 'bKashCallback'])->name('payment.bkash.callback');
        Route::post('/bkash/ipn', [BkashController::class, 'ipn'])->name('payment.bkash.ipn');
        Route::post('/amarpay/callback', [PaymentController::class, 'aMarPayCallback'])->name('payment.amarpay.callback');
        Route::post('/webhook', [PaymentController::class, 'paymentWebhook'])->name('payment.webhook');
    });

    // ================= PUBLIC BKASH CALLBACK ROUTES =================
    Route::prefix('bkash')->group(function () {
        Route::any('/public-callback', [PaymentController::class, 'bKashCallback'])->name('bkash.public.callback');
        Route::get('/public-success', [PaymentController::class, 'paymentSuccess'])->name('bkash.public.success');
        Route::get('/public-fail', [PaymentController::class, 'paymentFailed'])->name('bkash.public.fail');
    });

    // ================= TEST ROUTES =================
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/bkash-credentials', function () {
            return view('test.bkash-credentials');
        })->name('bkash.credentials');
        Route::post('/bkash-test-payment', [BkashController::class, 'testPayment'])->name('bkash.payment.test');
    });

    Route::get('/bkash/test-payment/{paymentID}', [PaymentController::class, 'testBkashPayment'])
        ->name('bkash.test.payment');
    Route::post('/test-bkash-process/{paymentID}', [PaymentController::class, 'processTestPayment'])
        ->name('bkash.test.process');

    // ================= SIMULATION ROUTES =================
    Route::prefix('simulate')->name('simulate.')->group(function () {
        Route::get('/bkash-payment', function () {
            return view('payment.simulate.bkash');
        })->name('bkash.payment');

        Route::post('/bkash-process', [PaymentController::class, 'simulateBkashPayment'])
            ->name('bkash.process');
    });

    // ================= TEST BKASH API ROUTE =================
    Route::get('/test-bkash-api', function () {
        // Test API logic
    })->name('test.bkash.api');
});

// ================= PUBLIC PAYMENT REDIRECT ROUTES =================
Route::get('/payment/success', function () {
    return redirect()->route('citizen.payments.success');
})->name('public.payment.success');
Route::get('/payment/failed', function () {
    return redirect()->route('citizen.payments.failed');
})->name('public.payment.failed');

// ================= DEBUG ROUTES =================
Route::get('/debug-test', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'Server is working',
        'time' => now()->toDateTimeString()
    ]);
});

Route::get('/debug-db', function() {
    try {
        \DB::connection()->getPdo();
        return response()->json([
            'success' => true,
            'message' => 'Database connected successfully',
            'database' => \DB::connection()->getDatabaseName()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// ================= FALLBACK 404 =================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::match(['get','post'],
    '/bkash/callback',
    [BkashTokenizePaymentController::class, 'callBack']
)->name('bkash.callback');


