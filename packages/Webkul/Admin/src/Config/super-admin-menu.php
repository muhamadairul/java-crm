<?php

return [
    [
        'key'        => 'dashboard',
        'name'       => 'Dashboard',
        'route'      => 'super_admin.dashboard.index',
        'sort'       => 1,
        'icon-class' => 'icon-dashboard',
    ],
    [
        'key'        => 'companies',
        'name'       => 'Perusahaan (Tenants)',
        'route'      => 'super_admin.companies.index',
        'sort'       => 2,
        'icon-class' => 'icon-contact',
    ],
    [
        'key'        => 'plans',
        'name'       => 'Paket Plan',
        'route'      => 'super_admin.plans.index',
        'sort'       => 3,
        'icon-class' => 'icon-settings',
    ],
    [
        'key'        => 'invoices',
        'name'       => 'Billing & Invoice',
        'route'      => 'super_admin.invoices.index',
        'sort'       => 4,
        'icon-class' => 'icon-quote',
    ],
];
