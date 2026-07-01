<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Controller;

/**
 * Home routes.
 */
Route::get('/', [Controller::class, 'redirectToLogin'])->name('java-crm.home');

/**
 * SaaS Tenant Registration Routes.
 */
Route::controller(\Webkul\Admin\Http\Controllers\RegistrationController::class)->prefix('register')->group(function () {
    Route::get('step1', 'showStep1')->name('tenant.register.step1');
    Route::post('step1', 'postStep1')->name('tenant.register.step1.post');
    Route::get('step2', 'showStep2')->name('tenant.register.step2');
    Route::post('step2', 'postStep2')->name('tenant.register.step2.post');
    Route::get('step3', 'showStep3')->name('tenant.register.step3');
    Route::post('step3', 'postStep3')->name('tenant.register.step3.post');
    Route::get('payment-pending/{invoice_id}', 'paymentPending')->name('tenant.register.payment_pending');
    Route::get('payment-check/{invoice_id}', 'checkPaymentStatus')->name('tenant.register.payment_check');
    Route::get('simulate-ewallet-redirect', 'simulateEwalletRedirect')->name('tenant.register.simulate_ewallet');
});

/**
 * Xendit Webhook Route.
 */
Route::post('xendit/webhook', [\Webkul\Admin\Http\Controllers\XenditWebhookController::class, 'handle'])
    ->name('xendit.webhook');
