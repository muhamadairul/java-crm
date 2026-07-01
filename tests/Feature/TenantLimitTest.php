<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Core\Models\Company;
use Webkul\Core\Models\Plan;
use Webkul\User\Models\User;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

it('enforces max users plan limit', function () {
    // 1. Create a Plan with max_users = 1
    $plan = Plan::create([
        'name'           => 'Limit Plan',
        'code'           => 'limit-plan-' . Str::random(5),
        'price'          => 10.0,
        'max_users'      => 1,
        'max_leads'      => 100,
        'max_storage_mb' => 500,
        'is_active'      => true,
    ]);

    // 2. Create Company
    $company = Company::create([
        'name'      => 'Limit Company',
        'slug'      => 'limit-company-' . Str::random(5),
        'plan_id'   => $plan->id,
        'is_active' => true,
    ]);

    // 3. Create Tenant Admin
    $tenantAdmin = User::create([
        'name'            => 'Tenant Admin',
        'email'           => 'tenantadmin-' . Str::random(5) . '@test.com',
        'password'        => bcrypt('password'),
        'role_id'         => 2, // Company Admin
        'company_id'      => $company->id,
        'status'          => 1,
        'view_permission' => 'global',
    ]);

    // 4. Try to POST user creation -> should be blocked by TenantLimitMiddleware
    $response = test()->actingAs($tenantAdmin)
        ->from(route('admin.dashboard.index')) // set referring URL
        ->post(route('admin.settings.users.store'), [
            'name'  => 'New User',
            'email' => 'newuser@limit.com',
        ]);

    $response->assertRedirect(route('admin.dashboard.index'));
    
    // Check that the error flash is set in session
    test()->assertTrue(session()->has('error'));
    test()->assertStringContainsString('Batas maksimum pengguna', session('error'));
});

it('enforces max leads plan limit', function () {
    // 1. Create a Plan with max_leads = 1
    $plan = Plan::create([
        'name'           => 'Lead Limit Plan',
        'code'           => 'lead-limit-plan-' . Str::random(5),
        'price'          => 10.0,
        'max_users'      => 5,
        'max_leads'      => 1,
        'max_storage_mb' => 500,
        'is_active'      => true,
    ]);

    // 2. Create Company
    $company = Company::create([
        'name'      => 'Lead Limit Company',
        'slug'      => 'lead-limit-company-' . Str::random(5),
        'plan_id'   => $plan->id,
        'is_active' => true,
    ]);

    // 3. Create Tenant Admin
    $tenantAdmin = User::create([
        'name'            => 'Tenant Admin',
        'email'           => 'tenantadmin-' . Str::random(5) . '@test.com',
        'password'        => bcrypt('password'),
        'role_id'         => 2, // Company Admin
        'company_id'      => $company->id,
        'status'          => 1,
        'view_permission' => 'global',
    ]);

    // 4. Create one Lead in database to reach the limit
    DB::table('leads')->insert([
        'title'      => 'Lead 1',
        'company_id' => $company->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 5. Try to POST lead creation -> should be blocked
    $response = test()->actingAs($tenantAdmin)
        ->from(route('admin.dashboard.index'))
        ->post(route('admin.leads.store'), [
            'title' => 'Lead 2',
        ]);

    $response->assertRedirect(route('admin.dashboard.index'));
    
    // Check that the error flash is set in session
    test()->assertTrue(session()->has('error'));
    test()->assertStringContainsString('Batas maksimum prospek', session('error'));
});
