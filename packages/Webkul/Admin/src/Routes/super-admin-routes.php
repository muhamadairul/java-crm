<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\SuperAdmin\SuperAdminController;
use Webkul\Admin\Http\Controllers\SuperAdmin\CompanyController;
use Webkul\Admin\Http\Controllers\SuperAdmin\PlanController;
use Webkul\Admin\Http\Controllers\SuperAdmin\InvoiceController;

// Guest Routes
Route::withoutMiddleware(['user'])->group(function () {
    Route::controller(SuperAdminController::class)->prefix('login')->group(function () {
        Route::get('', 'showLoginForm')->name('super_admin.session.create');
        Route::post('', 'login')->name('super_admin.session.store');
    });
});

// Authenticated Routes
Route::middleware(['user'])->group(function () {
    Route::post('logout', [SuperAdminController::class, 'logout'])->name('super_admin.session.destroy'); // Using POST for simpler form submission or DELETE
    Route::delete('logout', [SuperAdminController::class, 'logout']); // Fallback support for delete method
    
    // Dashboard
    Route::get('dashboard', [SuperAdminController::class, 'index'])->name('super_admin.dashboard.index');
    
    // Company Management
    Route::prefix('companies')->group(function () {
        Route::get('', [CompanyController::class, 'index'])->name('super_admin.companies.index');
        Route::post('{id}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('super_admin.companies.toggle_status');
        Route::get('{id}/edit', [CompanyController::class, 'edit'])->name('super_admin.companies.edit');
        Route::put('{id}', [CompanyController::class, 'update'])->name('super_admin.companies.update');
        Route::get('{id}', [CompanyController::class, 'show'])->name('super_admin.companies.show');
    });

    // Plan Management
    Route::prefix('plans')->group(function () {
        Route::get('', [PlanController::class, 'index'])->name('super_admin.plans.index');
        Route::get('{id}/edit', [PlanController::class, 'edit'])->name('super_admin.plans.edit');
        Route::put('{id}', [PlanController::class, 'update'])->name('super_admin.plans.update');
    });

    // Billing & Invoice Management
    Route::prefix('invoices')->group(function () {
        Route::get('', [InvoiceController::class, 'index'])->name('super_admin.invoices.index');
        Route::get('{id}', [InvoiceController::class, 'show'])->name('super_admin.invoices.show');
    });
});
