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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 10, 2)->default(0.00)->after('stripe_customer_id')->comment('User account balance');
            $table->decimal('bonus_balance', 10, 2)->default(0.00)->after('balance')->comment('Bonus balance from offers');
            $table->boolean('auto_debit_enabled')->default(false)->after('bonus_balance')->comment('Auto-debit for ad spend');
            $table->decimal('auto_debit_threshold', 10, 2)->nullable()->after('auto_debit_enabled')->comment('Minimum balance before auto-debit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance', 'bonus_balance', 'auto_debit_enabled', 'auto_debit_threshold']);
        });
    }
};
