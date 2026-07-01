<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Xendit Webhook Callback
Route::post('xendit/callback', [\Webkul\Core\Http\Controllers\PaymentController::class, 'callback'])->name('api.xendit.callback');
Route::post('xendit/simulate-success/{invoice_id}', [\Webkul\Core\Http\Controllers\PaymentController::class, 'simulateSuccess'])->name('api.xendit.simulate_success');

