<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables that need company_id for tenant isolation.
     */
    protected array $tables = [
        // User Package
        'users',
        'roles',
        'groups',

        // Lead Package
        'leads',
        'lead_pipelines',
        'lead_sources',
        'lead_types',

        // Contact Package
        'persons',
        'organizations',

        // Product Package
        'products',

        // Quote Package
        'quotes',

        // Activity Package
        'activities',

        // Email Package
        'emails',

        // Tag Package
        'tags',

        // Warehouse Package
        'warehouses',

        // EmailTemplate Package
        'email_templates',

        // Automation Package
        'workflows',

        // WebForm Package
        'web_forms',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'company_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedInteger('company_id')->nullable()->after('id');
                    $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                    $table->index('company_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'company_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropForeign([$tableName . '_company_id_foreign']);
                    $table->dropIndex([$tableName . '_company_id_index']);
                    $table->dropColumn('company_id');
                });
            }
        }
    }
};
