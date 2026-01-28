<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Dashboard APIs
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'getStats']);
        Route::get('/pending-applications', [DashboardController::class, 'getPendingApplications']);
        Route::get('/recent-payments', [DashboardController::class, 'getRecentPayments']);
        Route::get('/system-status', [DashboardController::class, 'getSystemStatus']);
    });
});