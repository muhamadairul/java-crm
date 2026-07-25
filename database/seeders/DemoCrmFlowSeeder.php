<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoCrmFlowSeeder extends Seeder
{
    /**
     * Run the database seeds for a complete CRM Flow demonstration.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // 1. Ensure Plan exists
        $planId = DB::table('plans')->where('code', 'enterprise')->value('id');
        if (! $planId) {
            $planId = DB::table('plans')->insertGetId([
                'name'           => 'Enterprise',
                'code'           => 'enterprise',
                'description'    => 'Enterprise Plan Demo',
                'price'          => 99.00,
                'billing_cycle'  => 'monthly',
                'max_users'      => 999,
                'max_leads'      => 99999,
                'max_storage_mb' => 10000,
                'is_active'      => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // 2. Create Company (Tenant)
        $companySlug = 'nusantara-tech';
        $companyId = DB::table('companies')->where('slug', $companySlug)->value('id');
        if (! $companyId) {
            $companyId = DB::table('companies')->insertGetId([
                'name'          => 'Nusantara Technology Solutions',
                'slug'          => $companySlug,
                'domain'        => 'nusantaratech.com',
                'email'         => 'contact@nusantaratech.com',
                'phone'         => '021-88997766',
                'address'       => 'Jl. Sudirman No. 45, Jakarta Selatan',
                'plan_id'       => $planId,
                'is_active'     => true,
                'trial_ends_at' => $now->copy()->addYear(),
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // Create Subscription
        DB::table('subscriptions')->updateOrInsert(
            ['company_id' => $companyId],
            [
                'plan_id'    => $planId,
                'status'     => 'active',
                'starts_at'  => $now,
                'ends_at'    => $now->copy()->addYear(),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // 3. Create Roles for Company
        $adminRoleId = DB::table('roles')->where('company_id', $companyId)->where('name', 'Company Admin')->value('id');
        if (! $adminRoleId) {
            $adminRoleId = DB::table('roles')->insertGetId([
                'name'            => 'Company Admin',
                'description'     => 'Administrator Perusahaan dengan akses penuh',
                'permission_type' => 'all',
                'company_id'      => $companyId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        $salesRoleId = DB::table('roles')->where('company_id', $companyId)->where('name', 'Sales Representative')->value('id');
        if (! $salesRoleId) {
            $salesRoleId = DB::table('roles')->insertGetId([
                'name'            => 'Sales Representative',
                'description'     => 'Sales Executive untuk mengelola Lead & Penawaran',
                'permission_type' => 'custom',
                'permissions'     => json_encode(['dashboard', 'leads', 'quotes', 'contacts', 'activities']),
                'company_id'      => $companyId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // 4. Create Users for Company
        $adminUserId = DB::table('users')->where('company_id', $companyId)->where('email', 'admin@nusantaratech.com')->value('id');
        if (! $adminUserId) {
            $adminUserId = DB::table('users')->insertGetId([
                'name'            => 'Ahmad Admin',
                'email'           => 'admin@nusantaratech.com',
                'password'        => Hash::make('password'),
                'status'          => 1,
                'role_id'         => $adminRoleId,
                'company_id'      => $companyId,
                'view_permission' => 'global',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        $salesUserId = DB::table('users')->where('company_id', $companyId)->where('email', 'sales@nusantaratech.com')->value('id');
        if (! $salesUserId) {
            $salesUserId = DB::table('users')->insertGetId([
                'name'            => 'Budi Sales Executive',
                'email'           => 'sales@nusantaratech.com',
                'password'        => Hash::make('password'),
                'status'          => 1,
                'role_id'         => $salesRoleId,
                'company_id'      => $companyId,
                'view_permission' => 'group',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // 5. Create Pipeline & Stages
        $pipelineId = DB::table('lead_pipelines')->where('company_id', $companyId)->where('name', 'Penjualan Enterprise B2B')->value('id');
        if (! $pipelineId) {
            $pipelineId = DB::table('lead_pipelines')->insertGetId([
                'name'       => 'Penjualan Enterprise B2B',
                'is_default' => true,
                'company_id' => $companyId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $stages = [
            ['name' => 'Lead Baru', 'code' => 'new', 'sort_order' => 1],
            ['name' => 'Diskusi & Kualifikasi', 'code' => 'qualified', 'sort_order' => 2],
            ['name' => 'Penawaran Terkirim', 'code' => 'proposal_sent', 'sort_order' => 3],
            ['name' => 'Negosiasi Kontrak', 'code' => 'negotiation', 'sort_order' => 4],
            ['name' => 'Berhasil (Won)', 'code' => 'won', 'sort_order' => 5],
            ['name' => 'Gagal (Lost)', 'code' => 'lost', 'sort_order' => 6],
        ];

        $stageIds = [];
        foreach ($stages as $stg) {
            $stgId = DB::table('lead_pipeline_stages')
                ->where('lead_pipeline_id', $pipelineId)
                ->where('code', $stg['code'])
                ->value('id');

            if (! $stgId) {
                $stgId = DB::table('lead_pipeline_stages')->insertGetId([
                    'name'             => $stg['name'],
                    'code'             => $stg['code'],
                    'sort_order'       => $stg['sort_order'],
                    'lead_pipeline_id' => $pipelineId,
                ]);
            }
            $stageIds[$stg['code']] = $stgId;
        }

        // 6. Lead Source & Type
        $sourceId = DB::table('lead_sources')->where('company_id', $companyId)->where('name', 'Website Inbound')->value('id');
        if (! $sourceId) {
            $sourceId = DB::table('lead_sources')->insertGetId([
                'name'       => 'Website Inbound',
                'company_id' => $companyId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $typeId = DB::table('lead_types')->where('company_id', $companyId)->where('name', 'Software B2B Enterprise')->value('id');
        if (! $typeId) {
            $typeId = DB::table('lead_types')->insertGetId([
                'name'       => 'Software B2B Enterprise',
                'company_id' => $companyId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 7. Create Product
        $productId = DB::table('products')->where('company_id', $companyId)->where('sku', 'CRM-ENT-100')->value('id');
        if (! $productId) {
            $productId = DB::table('products')->insertGetId([
                'name'        => 'JavaCRM Enterprise License (100 Users)',
                'sku'         => 'CRM-ENT-100',
                'description' => 'Lisensi tahunan JavaCRM Enterprise mencakup otomasi workflow & integrasi Xendit',
                'price'       => 50000000.00,
                'quantity'    => 10,
                'company_id'  => $companyId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        // 8. Create Organization & Person (Contact)
        $orgId = DB::table('organizations')->where('company_id', $companyId)->where('name', 'PT Megah Perkasa Indonesia')->value('id');
        if (! $orgId) {
            $orgId = DB::table('organizations')->insertGetId([
                'name'       => 'PT Megah Perkasa Indonesia',
                'address'    => json_encode(['address' => 'Gedung Wisma Perkasa Lt. 12, Jakarta Central']),
                'company_id' => $companyId,
                'user_id'    => $salesUserId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $personId = DB::table('persons')->where('company_id', $companyId)->where('name', 'Bambang Wijaya')->value('id');
        if (! $personId) {
            $personId = DB::table('persons')->insertGetId([
                'name'            => 'Bambang Wijaya',
                'job_title'       => 'IT Procurement Director',
                'emails'          => json_encode([['value' => 'bambang.wijaya@megahperkasa.co.id', 'label' => 'work']]),
                'contact_numbers' => json_encode([['value' => '081298765432', 'label' => 'mobile']]),
                'organization_id' => $orgId,
                'company_id'      => $companyId,
                'user_id'         => $salesUserId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // 9. Create Lead (Alur Prospek CRM)
        $leadId = DB::table('leads')->where('company_id', $companyId)->where('title', 'Pengadaan System CRM - PT Megah Perkasa')->value('id');
        if (! $leadId) {
            $leadId = DB::table('leads')->insertGetId([
                'title'                  => 'Pengadaan System CRM - PT Megah Perkasa',
                'description'            => 'Kebutuhan sistem CRM SaaS untuk 100 user Sales & Support PT Megah Perkasa',
                'lead_value'             => 50000000.00,
                'status'                 => 1, // Open
                'user_id'                => $salesUserId,
                'person_id'              => $personId,
                'lead_source_id'         => $sourceId,
                'lead_type_id'           => $typeId,
                'lead_pipeline_id'       => $pipelineId,
                'lead_pipeline_stage_id' => $stageIds['proposal_sent'], // Status: Penawaran Terkirim
                'company_id'             => $companyId,
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);

            // Link Product to Lead
            DB::table('lead_products')->insert([
                'lead_id'    => $leadId,
                'product_id' => $productId,
                'price'      => 50000000.00,
                'quantity'   => 1,
                'amount'     => 50000000.00,
            ]);
        }

        // 10. Create Activity (Jadwal Meeting Demo)
        $activityId = DB::table('activities')->where('company_id', $companyId)->where('title', 'Demo System CRM & Pembahasan Arsitektur')->value('id');
        if (! $activityId) {
            $activityId = DB::table('activities')->insertGetId([
                'title'       => 'Demo System CRM & Pembahasan Arsitektur',
                'type'        => 'meeting',
                'location'    => 'Zoom Meeting / On-site Office',
                'comment'     => 'Presentasi fitur Multi-Tenant, Otomasi Workflow, dan Integrasi Xendit',
                'schedule_from'=> $now->copy()->addDay()->setHour(10)->setMinute(0),
                'schedule_to'  => $now->copy()->addDay()->setHour(11)->setMinute(30),
                'is_done'     => false,
                'user_id'     => $salesUserId,
                'company_id'  => $companyId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // Attach Activity to Lead & Person
            DB::table('lead_activities')->insert([
                'lead_id'     => $leadId,
                'activity_id' => $activityId,
            ]);

            DB::table('person_activities')->insert([
                'person_id'   => $personId,
                'activity_id' => $activityId,
            ]);
        }

        // 11. Create Quotation (Penawaran Resmi)
        $quoteId = DB::table('quotes')->where('company_id', $companyId)->where('subject', 'Penawaran Resmi JavaCRM Enterprise - PT Megah Perkasa')->value('id');
        if (! $quoteId) {
            $quoteId = DB::table('quotes')->insertGetId([
                'subject'           => 'Penawaran Resmi JavaCRM Enterprise - PT Megah Perkasa',
                'description'       => 'Penawaran lisensi tahunan JavaCRM Enterprise 100 User',
                'sub_total'         => 50000000.00,
                'discount_percent'  => 0,
                'discount_amount'   => 0,
                'tax_amount'        => 5500000.00, // PPN 11%
                'adjustment_amount' => 0,
                'grand_total'       => 55500000.00,
                'user_id'           => $salesUserId,
                'person_id'         => $personId,
                'company_id'        => $companyId,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);

            // Link Quote to Lead
            DB::table('lead_quotes')->insert([
                'lead_id'  => $leadId,
                'quote_id' => $quoteId,
            ]);

            // Add Quote Item
            DB::table('quote_items')->insert([
                'name'             => 'JavaCRM Enterprise License (100 Users)',
                'sku'              => 'CRM-ENT-100',
                'quantity'         => 1,
                'price'            => 50000000.00,
                'total'            => 50000000.00,
                'discount_percent' => 0,
                'discount_amount'  => 0,
                'tax_amount'       => 5500000.00,
                'product_id'       => $productId,
                'quote_id'         => $quoteId,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }
    }
}
