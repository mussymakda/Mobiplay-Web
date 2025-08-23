<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, try to drop the table if it exists to clear any tablespace issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('payments');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2)->comment('Payment amount');
            $table->enum('type', ['deposit', 'auto_debit', 'bonus', 'ad_spend', 'refund'])->default('deposit');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('stripe_payment_id')->nullable()->comment('Stripe payment intent ID');
            $table->string('stripe_customer_id')->nullable()->comment('Stripe customer ID');
            $table->string('transaction_id')->nullable()->comment('Internal transaction ID');
            $table->foreignId('offer_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('bonus_amount', 10, 2)->nullable()->default(0.00)->comment('Bonus amount from offers');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable()->comment('Additional payment data');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('stripe_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
