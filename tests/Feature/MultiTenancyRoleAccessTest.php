<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Core\Models\Company;
use Webkul\User\Models\User;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

it('redirects unauthenticated users from tenant dashboard', function () {
    $response = test()->get(route('admin.dashboard.index'));
    $response->assertRedirect(route('admin.session.create'));
});

it('redirects unauthenticated users from super admin dashboard', function () {
    $response = test()->get(route('super_admin.dashboard.index'));
    $response->assertRedirect(route('super_admin.session.create'));
});

it('allows super admin to access super admin dashboard and blocks from tenant dashboard', function () {
    // 1. Create Super Admin User with random email
    $superAdmin = User::create([
        'name'            => 'Super Admin',
        'email'           => 'superadmin-' . Str::random(5) . '@test.com',
        'password'        => bcrypt('password'),
        'role_id'         => 1,
        'company_id'      => null,
        'status'          => 1,
        'view_permission' => 'global',
    ]);

    // 2. Access super admin dashboard
    test()->actingAs($superAdmin)
        ->get(route('super_admin.dashboard.index'))
        ->assertOK();

    // 3. Access tenant dashboard (should redirect to super admin dashboard)
    test()->actingAs($superAdmin)
        ->get(route('admin.dashboard.index'))
        ->assertRedirect(route('super_admin.dashboard.index'));
});

it('allows tenant admin to access tenant dashboard and blocks from super admin dashboard', function () {
    // 1. Create a Company and a Role with random slug
    $company = Company::create([
        'name'      => 'Test Company',
        'slug'      => 'test-company-' . Str::random(5),
        'is_active' => true,
    ]);

    // 2. Create Tenant Admin User with random email and global Company Admin role (ID = 2)
    $tenantAdmin = User::create([
        'name'            => 'Tenant Admin',
        'email'           => 'tenantadmin-' . Str::random(5) . '@test.com',
        'password'        => bcrypt('password'),
        'role_id'         => 2,
        'company_id'      => $company->id,
        'status'          => 1,
        'view_permission' => 'global',
    ]);

    // 3. Access tenant dashboard
    test()->actingAs($tenantAdmin)
        ->get(route('admin.dashboard.index'))
        ->assertOK();

    // 4. Access super admin dashboard (should redirect to tenant dashboard)
    test()->actingAs($tenantAdmin)
        ->get(route('super_admin.dashboard.index'))
        ->assertRedirect(route('admin.dashboard.index'));
});
