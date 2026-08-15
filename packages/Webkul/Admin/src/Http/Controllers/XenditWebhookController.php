<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Webkul\Core\Models\Company;
use Webkul\Core\Models\Invoice;
use Webkul\Core\Models\Subscription;
use Webkul\Core\Services\XenditService;

class XenditWebhookController extends Controller
{
    /**
     * Handle incoming webhook request from Xendit.
     */
    public function handle(Request $request): JsonResponse
    {
        Log::info('Xendit webhook received', [
            'headers' => $request->headers->all(),
            'body'    => $request->all(),
        ]);

        $callbackToken = $request->header('x-callback-token');
        if (!$callbackToken) {
            return response()->json(['message' => 'Missing x-callback-token header'], 400);
        }

        $xenditService = new XenditService();
        if (!$xenditService->verifyWebhookToken($callbackToken)) {
            return response()->json(['message' => 'Invalid callback token'], 401);
        }

        $payload = $this->resolveWebhookPayload($request);
        $event   = $payload['event'] ?? null;
        $data    = $payload['data'] ?? [];

        $referenceId      = $data['reference_id'] ?? null;
        $paymentRequestId = $data['payment_request_id'] ?? null;

        if (!$referenceId && !$paymentRequestId) {
            return response()->json(['message' => 'Missing reference_id or payment_request_id in data'], 400);
        }

        // Find invoice by reference_id (invoice_number) or payment_request_id (xendit_invoice_id)
        $invoice = null;
        if ($referenceId) {
            $invoice = Invoice::where('invoice_number', $referenceId)->first();
        }
        if (!$invoice && $paymentRequestId) {
            $invoice = Invoice::where('xendit_invoice_id', $paymentRequestId)->first();
        }

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // 1. Handle Payment Capture / Success
        if (in_array($event, ['payment.capture', 'payment_request.succeeded']) || ($data['status'] ?? '') === 'SUCCEEDED') {
            if ($invoice->status === 'paid') {
                return response()->json(['message' => 'Invoice already paid'], 200);
            }

            DB::beginTransaction();
            try {
                // Update Invoice
                $invoice->status           = 'paid';
                $invoice->paid_at          = now();
                $invoice->response_payment = json_encode($request->all());
                $invoice->notes            = json_encode($data);
                if (isset($data['channel_code'])) {
                    $invoice->bank_code = $data['channel_code'];
                }
                $invoice->save();

                // Update Subscription
                $subscription = Subscription::find($invoice->subscription_id);
                if ($subscription) {
                    $subscription->status    = 'active';
                    $subscription->starts_at = now();
                    
                    $plan   = $subscription->plan;
                    $endsAt = ($plan && $plan->billing_cycle === 'yearly') ? now()->addYear() : now()->addMonth();
                    $subscription->ends_at = $endsAt;
                    $subscription->save();
                }

                // Update Company
                $company = Company::find($invoice->company_id);
                if ($company) {
                    $company->is_active = true;
                    $company->save();

                    // Send notifications to Company Admin
                    $adminUser = \Webkul\User\Models\User::where('company_id', $company->id)
                        ->whereHas('role', function ($query) {
                            $query->where('name', 'Company Admin');
                        })->first();

                    if ($adminUser) {
                        try {
                            $adminUser->notify(new \Webkul\Admin\Notifications\InApp\InvoicePaid($invoice));
                            $adminUser->notify(new \Webkul\Admin\Notifications\InApp\TenantActivated($company));
                        } catch (\Exception $ne) {
                            Log::error('Failed to send webhook notifications: ' . $ne->getMessage());
                        }
                    }
                }

                DB::commit();
                return response()->json(['message' => 'Payment capture processed successfully'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Xendit processing error: ' . $e->getMessage());
                return response()->json(['message' => 'Internal server error: ' . $e->getMessage()], 500);
            }
        }

        // 2. Handle Payment Authorization
        if ($event === 'payment.authorization' || ($data['status'] ?? '') === 'AUTHORIZED') {
            $invoice->response_payment = json_encode($request->all());
            $invoice->notes            = json_encode($data);
            $invoice->save();

            Log::info('Payment authorized for invoice: ' . $invoice->invoice_number);
            return response()->json(['message' => 'Payment authorization recorded successfully'], 200);
        }

        // 3. Handle Payment Failure
        if ($event === 'payment.failure' || ($data['status'] ?? '') === 'FAILED') {
            $invoice->status           = 'failed';
            $invoice->response_payment = json_encode($request->all());
            $invoice->notes            = json_encode($data);
            $invoice->save();

            Log::warning('Payment failed for invoice: ' . $invoice->invoice_number, [
                'failure_code' => $data['failure_code'] ?? 'UNKNOWN',
            ]);
            return response()->json(['message' => 'Payment failure recorded successfully'], 200);
        }

        return response()->json(['message' => 'Event ignored'], 200);
    }

    /**
     * Resolve the event name and data array from various Xendit webhook body structures.
     * Supports:
     * - paymentCapture.value
     * - paymentAuthorization.value
     * - paymentFailure.value
     * - value (legacy/wrapper)
     * - direct root format
     */
    protected function resolveWebhookPayload(Request $request): array
    {
        $body = $request->all();

        if (isset($body['paymentCapture']['value'])) {
            $val = $body['paymentCapture']['value'];
            return [
                'event' => $val['event'] ?? 'payment.capture',
                'data'  => $val['data'] ?? [],
            ];
        }

        if (isset($body['paymentAuthorization']['value'])) {
            $val = $body['paymentAuthorization']['value'];
            return [
                'event' => $val['event'] ?? 'payment.authorization',
                'data'  => $val['data'] ?? [],
            ];
        }

        if (isset($body['paymentFailure']['value'])) {
            $val = $body['paymentFailure']['value'];
            return [
                'event' => $val['event'] ?? 'payment.failure',
                'data'  => $val['data'] ?? [],
            ];
        }

        if (isset($body['value'])) {
            $val = $body['value'];
            return [
                'event' => $val['event'] ?? $request->input('event'),
                'data'  => $val['data'] ?? $val,
            ];
        }

        return [
            'event' => $request->input('event'),
            'data'  => $request->input('data', $body),
        ];
    }
}
