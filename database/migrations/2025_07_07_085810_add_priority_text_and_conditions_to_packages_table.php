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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('priority_text')->after('priority_level')->nullable()->comment('Display text for priority like High, Mid, Low');
            $table->json('ad_showing_conditions')->after('description')->nullable()->comment('Conditions when ads can be shown: rush_hours, normal_hours, holidays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['priority_text', 'ad_showing_conditions']);
        });
    }
};
