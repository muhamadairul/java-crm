<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return array
     */
    public function run()
    {
        // 1. Create Plans
        $plans = [
            [
                'id'            => 1,
                'name'          => 'Free',
                'code'          => 'free',
                'description'   => 'Free Plan',
                'price'         => 0.00,
                'billing_cycle' => 'monthly',
                'max_users'     => 2,
                'max_leads'     => 100,
                'max_storage_mb'=> 500,
                'features'      => json_encode(['email' => true, 'automation' => false]),
                'is_active'     => true,
                'sort_order'    => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id'            => 2,
                'name'          => 'Pro',
                'code'          => 'pro',
                'description'   => 'Professional Plan',
                'price'         => 29.00,
                'billing_cycle' => 'monthly',
                'max_users'     => 10,
                'max_leads'     => 1000,
                'max_storage_mb'=> 2000,
                'features'      => json_encode(['email' => true, 'automation' => true]),
                'is_active'     => true,
                'sort_order'    => 2,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id'            => 3,
                'name'          => 'Enterprise',
                'code'          => 'enterprise',
                'description'   => 'Enterprise Plan',
                'price'         => 99.00,
                'billing_cycle' => 'monthly',
                'max_users'     => 999,
                'max_leads'     => 99999,
                'max_storage_mb'=> 10000,
                'features'      => json_encode(['email' => true, 'automation' => true]),
                'is_active'     => true,
                'sort_order'    => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->updateOrInsert(['code' => $plan['code']], $plan);
        }

        // 2. Create Company
        DB::table('companies')->updateOrInsert(
            ['slug' => 'javatekno'],
            [
                'name'          => 'Javatekno Mitra Solusi',
                'slug'          => 'javatekno',
                'domain'        => 'javatekno.co.id',
                'email'         => 'info@javatekno.co.id',
                'phone'         => '0211234567',
                'address'       => 'Jakarta, Indonesia',
                'plan_id'       => 2,
                'is_active'     => true,
                'trial_ends_at' => Carbon::now()->addDays(14),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]
        );

        $company = DB::table('companies')->where('slug', 'javatekno')->first();

        // 3. Create Subscription
        DB::table('subscriptions')->updateOrInsert(
            [
                'company_id' => $company->id,
                'plan_id'    => 2,
            ],
            [
                'status'     => 'active',
                'starts_at'  => Carbon::now(),
                'ends_at'    => Carbon::now()->addYear(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        return [
            'company_id' => $company->id,
            'plan_id'    => 2,
        ];
    }
}
