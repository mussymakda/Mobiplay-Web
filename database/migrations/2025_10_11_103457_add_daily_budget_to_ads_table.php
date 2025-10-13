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
        Schema::table('ads', function (Blueprint $table) {
            $table->decimal('daily_budget', 10, 2)->default(0)->after('budget')->comment('Daily spending limit');
            $table->decimal('daily_spent', 10, 2)->default(0)->after('daily_budget')->comment('Amount spent today');
            $table->date('last_reset_date')->nullable()->after('daily_spent')->comment('Last date when daily_spent was reset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['daily_budget', 'daily_spent', 'last_reset_date']);
        });
    }
};
