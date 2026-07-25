<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lead_pipelines', function (Blueprint $table) {
            // Drop global unique constraint on pipeline name to support multi-tenancy
            $table->dropUnique('lead_pipelines_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_pipelines', function (Blueprint $table) {
            $table->unique('name', 'lead_pipelines_name_unique');
        });
    }
};
