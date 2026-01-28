<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ApplicationManageController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All admin related routes are defined here
|
*/

Route::prefix('admin')->middleware(['auth', 'role:admin,secretary,citizen'])->name('admin.')->group(function() {
    
    // Dashboard Route
    Route::get('/dashboard', function () {
        // Role অনুযায়ী view return
        $user = auth()->user();
        if ($user->role === 'secretary') {
            return view('dashboards.secretary');
        } elseif ($user->role === 'admin') {
            return view('dashboards.admin');
        } elseif ($user->role === 'citizen') {
            return view('dashboards.citizen');
        }
        return view('admin.dashboard');
    })->name('dashboard');
    
    // ============================================
    // APPLICATION MANAGEMENT ROUTES
    // ============================================
    Route::prefix('applications')->name('applications.')->group(function() {
        Route::get('/', [ApplicationManageController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicationManageController::class, 'show'])->name('show');
        
        // Application Approval/Rejection
        Route::post('/{id}/approve', [ApplicationManageController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [ApplicationManageController::class, 'reject'])->name('reject');
        
        // Old methods for backward compatibility
        Route::post('/{application}/old-approve', [ApplicationManageController::class, 'oldApprove'])->name('old.approve');
        Route::post('/{application}/old-reject', [ApplicationManageController::class, 'oldReject'])->name('old.reject');
        
        // Application Status Update
        Route::put('/{id}/status', [ApplicationManageController::class, 'updateStatus'])->name('status.update');
        
        // Bulk Actions
        Route::post('/bulk-action', [ApplicationManageController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // ============================================
    // USER MANAGEMENT ROUTES
    // ============================================
    Route::prefix('users')->name('users.')->group(function() {
        // All users can view user list and details
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        
        // Only admin/super_admin can perform these actions
        Route::middleware(['check.role:admin,super_admin'])->group(function() {
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{user}/status', [UserController::class, 'updateStatus'])->name('status');
            Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/export', [UserController::class, 'export'])->name('export');
        });
        
        // Only super_admin can perform these actions
        Route::middleware(['check.role:super_admin'])->group(function() {
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
        });
    });
    
    // ============================================
    // PROFILE ROUTES
    // ============================================
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
    
    Route::put('/profile', function (\Illuminate\Http\Request $request) {
        // Profile update logic here
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:20',
        ]);
        
        $user->update($request->only(['name', 'email', 'mobile']));
        
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    })->name('profile.update');
    
    // Password Change Routes
    Route::get('/change-password', function () {
        return view('admin.change-password');
    })->name('change-password');
    
    Route::post('/change-password', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);
        
        return back()->with('success', 'Password changed successfully.');
    })->name('change-password.post');
    
    // ============================================
    // SETTINGS ROUTES
    // ============================================
    Route::get('/settings', function () {
        // Check user role
        $user = auth()->user();
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return view('admin.settings');
        }
        abort(403, 'Unauthorized access.');
    })->name('settings');
    
    Route::put('/settings', function (\Illuminate\Http\Request $request) {
        // Settings update logic
        $user = auth()->user();
        
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            abort(403, 'Unauthorized access.');
        }
        
        // Update settings logic here
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    })->name('settings.update');
});

// ============================================
// SUPER ADMIN ROUTES (separate group)
// ============================================
Route::prefix('super-admin')->middleware(['auth', 'role:super_admin'])->name('super_admin.')->group(function() {
    
    // Super Admin Dashboard
    Route::get('/dashboard', function () {
        return view('dashboards.super_admin');
    })->name('dashboard');
    
    // Super Admin Application Management
    Route::prefix('applications')->name('applications.')->group(function() {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'index'])->name('index');
        Route::get('/search', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'search'])->name('search');
        Route::get('/statistics', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'reject'])->name('reject');
        Route::post('/{id}/update-payment', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'updatePayment'])->name('update-payment');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [\App\Http\Controllers\SuperAdmin\ApplicationManageController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // Super Admin User Management (পূর্ণ অধিকার সহ)
    Route::prefix('users')->name('users.')->group(function() {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\UserController::class, 'index'])->name('index');
        Route::get('/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [\App\Http\Controllers\SuperAdmin\UserController::class, 'edit'])->name('edit');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\UserController::class, 'store'])->name('store');
        Route::put('/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'destroy'])->name('destroy');
        Route::put('/{user}/status', [\App\Http\Controllers\SuperAdmin\UserController::class, 'updateStatus'])->name('status');
        Route::post('/bulk-action', [\App\Http\Controllers\SuperAdmin\UserController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [\App\Http\Controllers\SuperAdmin\UserController::class, 'export'])->name('export');
    });
    
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function() {
        Route::get('/', function () {
            return view('super_admin.settings.index');
        })->name('index');
        
        // Union Settings
        Route::get('/union', [\App\Http\Controllers\SuperAdmin\UnionSettingController::class, 'index'])->name('union');
        Route::put('/union/update', [\App\Http\Controllers\SuperAdmin\UnionSettingController::class, 'update'])->name('union.update');
        Route::post('/union/delete-image', [\App\Http\Controllers\SuperAdmin\UnionSettingController::class, 'deleteImage'])->name('union.delete-image');
        Route::get('/union/reset', [\App\Http\Controllers\SuperAdmin\UnionSettingController::class, 'reset'])->name('union.reset');
    });
    
    // Certificate Types Management
    Route::prefix('certificates')->name('certificates.')->group(function() {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\CertificateController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\CertificateController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\CertificateController::class, 'store'])->name('store');
        Route::get('/{certificate}/edit', [\App\Http\Controllers\SuperAdmin\CertificateController::class, 'edit'])->name('edit');
        Route::put('/{certificate}', [\App\Http\Controllers\SuperAdmin\CertificateController::class, 'update'])->name('update');
        Route::delete('/{certificate}', [\App\Http\Controllers\SuperAdmin\CertificateController::class, 'destroy'])->name('destroy');
    });
});

// ============================================
// FALLBACK ROUTES
// ============================================
Route::fallback(function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->role === 'super_admin') {
            return redirect()->route('super_admin.dashboard');
        } elseif (in_array($user->role, ['admin', 'secretary', 'citizen'])) {
            return redirect()->route('admin.dashboard');
        }
    }
    
    return redirect('/login');
});