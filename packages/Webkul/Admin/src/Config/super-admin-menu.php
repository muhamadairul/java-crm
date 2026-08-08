<?php

return [
    [
        'key'        => 'dashboard',
        'name'       => 'admin::app.super_admin.menu.dashboard',
        'route'      => 'super_admin.dashboard.index',
        'sort'       => 1,
        'icon-class' => 'icon-dashboard',
    ],
    [
        'key'        => 'companies',
        'name'       => 'admin::app.super_admin.menu.companies',
        'route'      => 'super_admin.companies.index',
        'sort'       => 2,
        'icon-class' => 'icon-contact',
    ],
    [
        'key'        => 'plans',
        'name'       => 'admin::app.super_admin.menu.plans',
        'route'      => 'super_admin.plans.index',
        'sort'       => 3,
        'icon-class' => 'icon-product',
    ],
    [
        'key'        => 'invoices',
        'name'       => 'admin::app.super_admin.menu.invoices',
        'route'      => 'super_admin.invoices.index',
        'sort'       => 4,
        'icon-class' => 'icon-quote',
    ],
];
