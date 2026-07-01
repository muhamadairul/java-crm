<?php

use Illuminate\Support\Str;
use Webkul\Core\Models\Company;
use Webkul\Core\Models\Plan;
use Webkul\User\Models\User;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

it('blocks unauthenticated and tenant users from company detail view', function () {
    // 1. Create a Company and a Plan
    $plan = Plan::create([
        'name'          => 'Test Plan',
        'code'          => 'test-plan',
        'price'         => 0.00,
        'billing_cycle' => 'monthly',
        'max_users'     => 5,
        'max_leads'     => 100,
        'is_active'     => true,
    ]);

    $company = Company::create([
        'name'      => 'Test Company',
        'slug'      => 'test-company-' . Str::random(5),
        'plan_id'   => $plan->id,
        'is_active' => true,
    ]);

    // 2. Unauthenticated check
    test()->get(route('super_admin.companies.show', ['id' => $company->id]))
        ->assertRedirect(route('super_admin.session.create'));

    // 3. Tenant Admin check
    $tenantAdmin = User::create([
        'name'            => 'Tenant Admin',
        'email'           => 'tenantadmin-' . Str::random(5) . '@test.com',
        'password'        => bcrypt('password'),
        'role_id'         => 2,
        'company_id'      => $company->id,
        'status'          => 1,
        'view_permission' => 'global',
    ]);

    test()->actingAs($tenantAdmin)
        ->get(route('super_admin.companies.show', ['id' => $company->id]))
        ->assertRedirect(route('admin.dashboard.index'));
});

it('allows super admin to access company detail view', function () {
    // 1. Create a Plan and Company
    $plan = Plan::create([
        'name'          => 'Test Plan',
        'code'          => 'test-plan',
        'price'         => 0.00,
        'billing_cycle' => 'monthly',
        'max_users'     => 5,
        'max_leads'     => 100,
        'is_active'     => true,
    ]);

    $company = Company::create([
        'name'      => 'Test Company',
        'slug'      => 'test-company-' . Str::random(5),
        'plan_id'   => $plan->id,
        'is_active' => true,
    ]);

    // 2. Create Super Admin User
    $superAdmin = User::create([
        'name'            => 'Super Admin',
        'email'           => 'superadmin-' . Str::random(5) . '@test.com',
        'password'        => bcrypt('password'),
        'role_id'         => 1,
        'company_id'      => null,
        'status'          => 1,
        'view_permission' => 'global',
    ]);

    // 3. Access company detail
    test()->actingAs($superAdmin)
        ->get(route('super_admin.companies.show', ['id' => $company->id]))
        ->assertOK()
        ->assertSee('Detail Perusahaan: Test Company')
        ->assertSee('test-company');
});
