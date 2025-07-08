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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['first_deposit', 'reload_bonus', 'percentage_bonus', 'fixed_bonus'])->default('percentage_bonus');
            $table->decimal('bonus_percentage', 5, 2)->nullable()->comment('Percentage bonus (e.g., 50.00 for 50%)');
            $table->decimal('bonus_fixed_amount', 10, 2)->nullable()->comment('Fixed bonus amount');
            $table->decimal('minimum_deposit', 10, 2)->nullable()->comment('Minimum deposit required');
            $table->decimal('maximum_bonus', 10, 2)->nullable()->comment('Maximum bonus amount');
            $table->datetime('valid_from')->default(now());
            $table->datetime('valid_until');
            $table->integer('usage_limit')->nullable()->comment('Total times this offer can be used');
            $table->integer('used_count')->default(0)->comment('Times this offer has been used');
            $table->boolean('is_active')->default(true);
            $table->json('conditions')->nullable()->comment('Additional conditions for the offer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
