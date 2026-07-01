<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Webkul\Core\Models\Invoice;
use Webkul\Core\Models\Subscription;

class PaymentController extends Controller
{
    /**
     * Handle Xendit payment webhook callback
     */
    public function callback(Request $request): JsonResponse
    {
        Log::info('Xendit Webhook Received', [
            'headers' => $request->headers->all(),
            'payload' => $request->all()
        ]);

        // Validate webhook token if configured
        $expectedToken = env('XENDIT_CALLBACK_TOKEN');
        if ($expectedToken && $request->header('x-callback-token') !== $expectedToken) {
            Log::warning('Xendit webhook token mismatch');
            return response()->json(['success' => false, 'message' => 'Unauthorized token'], 401);
        }

        $payload = $request->all();

        // 1. Determine invoice number/reference ID and payment status
        $referenceId = $payload['reference_id'] ?? ($payload['external_id'] ?? null);
        $xenditInvoiceId = $payload['id'] ?? ($payload['data']['payment_request_id'] ?? null);
        
        // Handle nested payment request callback structure
        if (isset($payload['data'])) {
            $referenceId = $payload['data']['reference_id'] ?? $referenceId;
            $status = $payload['data']['status'] ?? null;
        } else {
            $status = $payload['status'] ?? null;
        }

        if (!$referenceId && !$xenditInvoiceId) {
            return response()->json(['success' => false, 'message' => 'Reference ID or External ID not found'], 400);
        }

        // 2. Find the invoice in our database
        $invoice = null;
        if ($referenceId) {
            $invoice = Invoice::where('invoice_number', $referenceId)->first();
        }
        if (!$invoice && $xenditInvoiceId) {
            $invoice = Invoice::where('xendit_invoice_id', $xenditInvoiceId)->first();
        }

        if (!$invoice) {
            Log::error('Xendit Callback Error: Invoice not found for Reference ID ' . $referenceId);
            return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }

        // 3. Process payment status
        // Xendit status values: PAID, SUCCEEDED, COMPLETED, ACTIVE
        $isSuccessful = in_array(strtoupper($status), ['PAID', 'SUCCEEDED', 'COMPLETED', 'ACTIVE']);

        if ($isSuccessful) {
            $invoice->status = 'paid';
            $invoice->paid_at = now();
            $invoice->payment_method = $payload['payment_method'] ?? ($payload['data']['channel_code'] ?? $invoice->payment_method);
            $invoice->save();

            // Update associated subscription status
            if ($invoice->subscription) {
                $subscription = $invoice->subscription;
                $subscription->status = 'active';
                $subscription->starts_at = now();
                
                // Add 1 month to ends_at
                $billingCycle = $subscription->plan->billing_cycle ?? 'monthly';
                $subscription->ends_at = $billingCycle === 'yearly' ? now()->addYear() : now()->addMonth();
                $subscription->save();

                // Update company active status
                $company = $invoice->company;
                if ($company) {
                    $company->is_active = true;
                    $company->save();
                }
            }

            Log::info('Invoice marked as paid and subscription activated.', ['invoice_id' => $invoice->id]);
            return response()->json(['success' => true, 'message' => 'Payment processed successfully']);
        }

        Log::warning('Xendit Callback Status is not successful', ['status' => $status]);
        return response()->json(['success' => true, 'message' => 'Status received: ' . $status]);
    }

    /**
     * Simulate a successful webhook payment in testing mode
     */
    public function simulateSuccess(Request $request, $invoiceId): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $invoice = Invoice::find($invoiceId);
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }

        // Send a simulated request to our callback method
        $mockPayload = [
            'reference_id'   => $invoice->invoice_number,
            'id'             => $invoice->xendit_invoice_id,
            'status'         => 'SUCCEEDED',
            'payment_method' => $invoice->payment_method ?? 'SIMULATOR',
        ];

        // Perform internal request handling to keep logic centralized
        $subRequest = Request::create(route('api.xendit.callback'), 'POST', $mockPayload);
        $subRequest->headers->set('Content-Type', 'application/json');
        
        $response = $this->callback($subRequest);

        if ($request->expectsJson()) {
            return $response;
        }

        session()->flash('success', 'Simulasi Pembayaran Berhasil! Akun Admin Perusahaan Anda telah diaktifkan.');
        
        // Log in the user immediately
        $adminUser = \Webkul\User\Models\User::where('company_id', $invoice->company_id)->first();
        if ($adminUser) {
            auth()->guard('user')->login($adminUser);
        }

        return redirect()->route('admin.dashboard.index');
    }
}
