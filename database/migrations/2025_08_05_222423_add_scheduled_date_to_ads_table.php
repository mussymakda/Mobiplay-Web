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
<<<<<<<< HEAD:database/migrations/2025_10_03_151903_add_verification_fields_to_drivers_table.php
        Schema::table('drivers', function (Blueprint $table) {
            //
========
        Schema::table('ads', function (Blueprint $table) {
            $table->date('scheduled_date')->nullable()->after('status');
>>>>>>>> bdd4171edc9db61cf35e2c93be6fc2724fe5c370:database/migrations/2025_08_05_222423_add_scheduled_date_to_ads_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2025_10_03_151903_add_verification_fields_to_drivers_table.php
        Schema::table('drivers', function (Blueprint $table) {
            //
========
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('scheduled_date');
>>>>>>>> bdd4171edc9db61cf35e2c93be6fc2724fe5c370:database/migrations/2025_08_05_222423_add_scheduled_date_to_ads_table.php
        });
    }
};
