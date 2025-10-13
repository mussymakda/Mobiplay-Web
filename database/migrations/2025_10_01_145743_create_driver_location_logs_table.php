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
        Schema::create('driver_location_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');

            // GPS coordinates
            $table->decimal('latitude', 10, 8)->index();
            $table->decimal('longitude', 11, 8)->index();
            $table->decimal('accuracy', 8, 2)->nullable(); // GPS accuracy in meters
            $table->decimal('speed', 8, 2)->nullable(); // Speed in km/h from GPS
            $table->decimal('heading', 6, 2)->nullable(); // Direction/bearing in degrees (0-360)

            // Tracking metadata
            $table->string('source', 50)->default('app'); // Source of location: app, admin, system
            $table->timestamp('recorded_at')->index(); // When location was recorded

            // Movement analytics
            $table->decimal('distance_from_previous', 12, 2)->nullable(); // Distance in meters from last location
            $table->integer('time_difference_seconds')->nullable(); // Time since last location
            $table->decimal('estimated_speed_kmh', 8, 2)->nullable(); // Calculated speed based on movement

            // Security & validation
            $table->boolean('is_suspicious')->default(false)->index(); // Flag for suspicious movement
            $table->json('metadata')->nullable(); // Additional data (IP, user agent, etc.)

            $table->timestamps();

            // Indexes for performance
            $table->index(['driver_id', 'recorded_at']);
            $table->index(['driver_id', 'is_suspicious']);
            $table->index(['latitude', 'longitude']); // For geographic queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_location_logs');
    }
};
