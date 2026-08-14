<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Facades\DB;
use Webkul\Lead\Models\Pipeline;
use Webkul\Lead\Models\Stage;
use Webkul\User\Models\Group;

class CompanyDefaultSeeder
{
    /**
     * Seed initial default data for a newly created tenant company.
     */
    public static function seed(int $companyId, int $userId): void
    {
        // 1. Seed Default Pipeline & Stages
        $pipeline = Pipeline::create([
            'name'        => 'Sales Pipeline',
            'rotten_days' => 30,
            'is_default'  => 1,
            'company_id'  => $companyId,
        ]);

        $stages = [
            ['code' => 'new', 'name' => 'New', 'probability' => 10, 'sort_order' => 1],
            ['code' => 'contacted', 'name' => 'Contacted', 'probability' => 30, 'sort_order' => 2],
            ['code' => 'qualified', 'name' => 'Qualified', 'probability' => 50, 'sort_order' => 3],
            ['code' => 'proposal_sent', 'name' => 'Proposal Sent', 'probability' => 75, 'sort_order' => 4],
            ['code' => 'won', 'name' => 'Won', 'probability' => 100, 'sort_order' => 5],
            ['code' => 'lost', 'name' => 'Lost', 'probability' => 0, 'sort_order' => 6],
        ];

        foreach ($stages as $stageData) {
            Stage::create(array_merge($stageData, [
                'lead_pipeline_id' => $pipeline->id,
            ]));
        }

        // 2. Seed Default Lead Sources
        $sources = ['Website', 'WhatsApp', 'Referral', 'Social Media', 'Email Campaign'];
        foreach ($sources as $sourceName) {
            DB::table('lead_sources')->insert([
                'name'       => $sourceName,
                'company_id' => $companyId,
            ]);
        }

        // 3. Seed Default Lead Types
        $types = ['New Business', 'Existing Business'];
        foreach ($types as $typeName) {
            DB::table('lead_types')->insert([
                'name'       => $typeName,
                'company_id' => $companyId,
            ]);
        }

        // 4. Seed Default User Groups
        Group::create([
            'name'        => 'Sales Team',
            'description' => 'Tim penjualan utama perusahaan.',
            'company_id'  => $companyId,
        ]);

        Group::create([
            'name'        => 'Support Team',
            'description' => 'Tim layanan & dukungan pelanggan.',
            'company_id'  => $companyId,
        ]);

        // 5. Seed Default Tags
        $tags = [
            ['name' => 'Hot Lead', 'color' => '#ef4444'],
            ['name' => 'Warm Lead', 'color' => '#f59e0b'],
            ['name' => 'VIP', 'color' => '#8b5cf6'],
        ];

        foreach ($tags as $tagData) {
            DB::table('tags')->insert(array_merge($tagData, [
                'company_id' => $companyId,
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => $userId,
            ]));
        }
    }
}
