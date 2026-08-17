<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\SuperAdmin\SuperAdminController;
use Webkul\Admin\Http\Controllers\SuperAdmin\CompanyController;
use Webkul\Admin\Http\Controllers\SuperAdmin\PlanController;
use Webkul\Admin\Http\Controllers\SuperAdmin\InvoiceController;
use Webkul\Admin\Http\Controllers\SuperAdmin\AuditLogController;
use Webkul\Admin\Http\Controllers\LocaleController;

// Guest Routes
Route::withoutMiddleware(['user'])->group(function () {
    Route::controller(SuperAdminController::class)->prefix('login')->group(function () {
        Route::get('', 'showLoginForm')->name('super_admin.session.create');
        Route::post('', 'login')->name('super_admin.session.store');
    });
});

// Authenticated Routes
Route::middleware(['user'])->group(function () {
    Route::post('logout', [SuperAdminController::class, 'logout'])->name('super_admin.session.destroy');
    Route::delete('logout', [SuperAdminController::class, 'logout']);
    
    // Locale Switcher
    Route::get('switch-locale/{locale}', [LocaleController::class, 'switch'])->name('super_admin.switch_locale');

    // Dashboard
    Route::get('dashboard', [SuperAdminController::class, 'index'])->name('super_admin.dashboard.index');
    
    // Company Management (SA-03)
    Route::prefix('companies')->group(function () {
        Route::get('', [CompanyController::class, 'index'])->name('super_admin.companies.index');
        Route::get('create', [CompanyController::class, 'create'])->name('super_admin.companies.create');
        Route::post('', [CompanyController::class, 'store'])->name('super_admin.companies.store');
        Route::post('{id}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('super_admin.companies.toggle_status');
        Route::get('{id}/edit', [CompanyController::class, 'edit'])->name('super_admin.companies.edit');
        Route::put('{id}', [CompanyController::class, 'update'])->name('super_admin.companies.update');
        Route::get('{id}', [CompanyController::class, 'show'])->name('super_admin.companies.show');
        Route::delete('{id}', [CompanyController::class, 'destroy'])->name('super_admin.companies.destroy');
    });

    // Plan Management (SA-05)
    Route::prefix('plans')->group(function () {
        Route::get('', [PlanController::class, 'index'])->name('super_admin.plans.index');
        Route::get('create', [PlanController::class, 'create'])->name('super_admin.plans.create');
        Route::post('', [PlanController::class, 'store'])->name('super_admin.plans.store');
        Route::post('{id}/toggle-status', [PlanController::class, 'toggleStatus'])->name('super_admin.plans.toggle_status');
        Route::get('{id}/edit', [PlanController::class, 'edit'])->name('super_admin.plans.edit');
        Route::put('{id}', [PlanController::class, 'update'])->name('super_admin.plans.update');
    });

    // Billing & Invoice Management (SA-06)
    Route::prefix('invoices')->group(function () {
        Route::get('', [InvoiceController::class, 'index'])->name('super_admin.invoices.index');
        Route::post('{id}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('super_admin.invoices.mark_paid');
        Route::get('{id}', [InvoiceController::class, 'show'])->name('super_admin.invoices.show');
    });

    // Audit Logs (SA-07)
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('super_admin.audit_logs.index');
});
