<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Core\Models\Company;
use Webkul\Core\Models\Plan;
use Webkul\Core\Models\Subscription;
use Webkul\Core\Models\Invoice;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

it('rejects xendit webhook with invalid callback token', function () {
    // Set XENDIT_WEBHOOK_TOKEN env for testing
    putenv('XENDIT_WEBHOOK_TOKEN=valid-token-123');

    $response = test()->postJson(route('xendit.webhook'), [
        'event' => 'payment_request.succeeded',
        'data'  => [
            'reference_id' => 'INV-TEST',
        ]
    ], [
        'x-callback-token' => 'invalid-token'
    ]);

    $response->assertStatus(401);
});

it('processes xendit webhook payment_request.succeeded successfully', function () {
    // 1. Set configured webhook token
    putenv('XENDIT_WEBHOOK_TOKEN=valid-token-123');

    // 2. Create Plan
    $plan = Plan::create([
        'name'          => 'Test Pro Plan',
        'code'          => 'test-pro',
        'price'         => 10.00,
        'billing_cycle' => 'monthly',
        'max_users'     => 5,
        'max_leads'     => 100,
        'is_active'     => true,
    ]);

    // 3. Create inactive Company
    $company = Company::create([
        'name'      => 'Pending Company',
        'slug'      => 'pending-company-' . Str::random(5),
        'plan_id'   => $plan->id,
        'is_active' => false,
    ]);

    // 4. Create pending Subscription
    $subscription = Subscription::create([
        'company_id' => $company->id,
        'plan_id'    => $plan->id,
        'status'     => 'pending',
        'starts_at'  => now(),
        'ends_at'    => now()->addMonth(),
    ]);

    // 5. Create pending Invoice
    $invoice = Invoice::create([
        'company_id'      => $company->id,
        'subscription_id' => $subscription->id,
        'invoice_number'  => 'INV-' . strtoupper(Str::random(10)),
        'amount'          => 10.00,
        'currency'        => 'USD',
        'status'          => 'pending',
        'payment_method'  => 'CARD',
    ]);

    // 6. Post valid webhook request
    $response = test()->postJson(route('xendit.webhook'), [
        'event' => 'payment_request.succeeded',
        'data'  => [
            'reference_id' => $invoice->invoice_number,
            'id'           => 'pr-mock123',
            'status'       => 'SUCCEEDED',
        ]
    ], [
        'x-callback-token' => 'valid-token-123'
    ]);

    $response->assertStatus(200)
             ->assertJsonPath('message', 'Payment processed successfully');

    // 7. Verify DB updates
    $invoice->refresh();
    test()->assertEquals('paid', $invoice->status);
    test()->assertNotNull($invoice->paid_at);

    $subscription->refresh();
    test()->assertEquals('active', $subscription->status);

    $company->refresh();
    test()->assertTrue((bool) $company->is_active);
});
