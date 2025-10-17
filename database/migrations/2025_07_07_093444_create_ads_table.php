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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('campaign_name');
            $table->string('media_type')->default('image'); // image, video
            $table->string('media_path')->nullable(); // path to uploaded image/video
            $table->string('cta_url'); // Call to Action URL
            $table->string('qr_code_url')->nullable(); // Generated QR code URL
            $table->decimal('latitude', 10, 8)->nullable(); // Location latitude
            $table->decimal('longitude', 11, 8)->nullable(); // Location longitude
            $table->string('location_name')->nullable(); // Human readable location name
            $table->integer('radius_km')->default(5); // Radius in kilometers
            $table->enum('status', ['pending', 'active', 'paused', 'completed', 'rejected'])->default('pending');
            $table->decimal('budget', 10, 2)->default(0); // Total budget allocated
            $table->decimal('spent', 10, 2)->default(0); // Amount spent so far
            $table->integer('impressions')->default(0); // Number of times shown on screens
            $table->integer('qr_scans')->default(0); // Number of QR code scans
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['latitude', 'longitude']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
