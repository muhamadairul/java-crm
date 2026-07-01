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

        $event = $request->input('event');
        $data = $request->input('data', []);

        if ($event === 'payment_request.succeeded') {
            $referenceId = $data['reference_id'] ?? null;
            if (!$referenceId) {
                return response()->json(['message' => 'Missing reference_id in data'], 400);
            }

            $invoice = Invoice::where('invoice_number', $referenceId)->first();
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            if ($invoice->status === 'paid') {
                return response()->json(['message' => 'Invoice already paid'], 200);
            }

            // Update database records
            DB::beginTransaction();
            try {
                // Update Invoice
                $invoice->status = 'paid';
                $invoice->paid_at = now();
                $invoice->notes = json_encode($data);
                $invoice->save();

                // Update Subscription
                $subscription = Subscription::find($invoice->subscription_id);
                if ($subscription) {
                    $subscription->status = 'active';
                    $subscription->starts_at = now();
                    
                    // Set end date depending on billing cycle
                    $plan = $subscription->plan;
                    $endsAt = now()->addMonth();
                    if ($plan && $plan->billing_cycle === 'yearly') {
                        $endsAt = now()->addYear();
                    }
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
                return response()->json(['message' => 'Payment processed successfully'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Xendit processing error: ' . $e->getMessage());
                return response()->json(['message' => 'Internal server error: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['message' => 'Event ignored'], 200);
    }
}
