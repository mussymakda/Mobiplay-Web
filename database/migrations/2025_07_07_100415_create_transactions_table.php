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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ad_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // 'deposit', 'ad_spend', 'bonus', 'refund'
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('completed'); // 'pending', 'completed', 'failed'
            $table->string('reference')->nullable(); // Payment reference, transaction ID
            $table->json('metadata')->nullable(); // Additional data like stripe info
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
