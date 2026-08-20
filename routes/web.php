<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlertManagementController;
use App\Http\Controllers\AnalystController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IntrusionController;
use App\Http\Controllers\IpManagementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role?->slug;
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'analyst' => redirect()->route('analyst.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }
    return Inertia::render('Welcome', [
        'projectName' => 'Development of a Web-Based Intrusion Detection and Alert System',
        'studentName' => 'Catherine Emmanuel',
        'matricNumber' => 'SW/HND/F24/0085',
        'supervisorName' => 'Mrs. Mohammed M. O.',
    ]);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('can:view-admin-dashboard')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/alerts', [AdminController::class, 'alerts'])->name('alerts');
        Route::get('/threats', [AdminController::class, 'threats'])->name('threats');
        Route::get('/blocked-ips', [AdminController::class, 'blockedIps'])->name('blocked-ips');
        Route::get('/security-logs', [AdminController::class, 'securityLogs'])->name('security-logs');
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
        Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('/threat-rules', [AdminController::class, 'threatRules'])->name('threat-rules');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/reports/daily', [ReportController::class, 'exportDaily'])->name('reports.daily');
        Route::get('/reports/weekly', [ReportController::class, 'exportWeekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [ReportController::class, 'exportMonthly'])->name('reports.monthly');

        Route::resource('user-management', UserManagementController::class)
            ->parameters(['user-management' => 'user'])
            ->names([
                'index' => 'user-management.index',
                'create' => 'user-management.create',
                'store' => 'user-management.store',
                'edit' => 'user-management.edit',
                'update' => 'user-management.update',
                'destroy' => 'user-management.destroy',
            ]);

        Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    Route::prefix('analyst')->name('analyst.')->middleware('can:view-analyst-dashboard')->group(function () {
        Route::get('/dashboard', [AnalystController::class, 'dashboard'])->name('dashboard');
        Route::get('/incidents', [AnalystController::class, 'incidents'])->name('incidents');
        Route::get('/alerts', [AnalystController::class, 'reviewAlerts'])->name('alerts');
    });

    Route::prefix('user')->name('user.')->middleware('can:view-user-dashboard')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::get('/activity', [UserController::class, 'activity'])->name('activity');
    });

    Route::post('/alerts/{alert}/mark-read', [AlertManagementController::class, 'markAsRead'])->name('alerts.mark-read');
    Route::post('/alerts/{alert}/resolve', [AlertManagementController::class, 'resolve'])->name('alerts.resolve');
    Route::patch('/alerts/{alert}', [AlertManagementController::class, 'update'])->name('alerts.update');

    Route::patch('/intrusions/{event}/resolve', [IntrusionController::class, 'resolve'])->name('intrusions.resolve');

    Route::post('/blocked-ips', [IpManagementController::class, 'store'])->name('blocked-ips.store');
    Route::delete('/blocked-ips/{blockedIp}', [IpManagementController::class, 'unblock'])->name('blocked-ips.unblock');

    Route::match(['put', 'patch'], '/settings/{systemSetting}', [SettingController::class, 'update'])->name('settings.update');
});