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
        Schema::create('impressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'display', 'qr_scan'
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('location_data')->nullable(); // GPS coordinates, city, etc.
            $table->json('device_info')->nullable(); // Device type, OS, browser
            $table->decimal('cost', 8, 4)->default(0); // Cost per impression/scan
            $table->timestamp('viewed_at');
            $table->timestamps();
            
            $table->index(['ad_id', 'type']);
            $table->index(['user_id', 'viewed_at']);
            $table->index(['ad_id', 'viewed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impressions');
    }
};
