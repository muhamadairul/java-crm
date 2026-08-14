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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('bank_code')->nullable()->after('payment_method');
            $table->text('response_request')->nullable()->after('notes');
            $table->text('response_payment')->nullable()->after('response_request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('bank_code');
            $table->dropColumn('response_request');
            $table->dropColumn('response_payment');
        });
    }
};
