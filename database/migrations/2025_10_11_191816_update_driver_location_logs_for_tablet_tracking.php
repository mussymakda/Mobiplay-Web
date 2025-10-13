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
        Schema::table('driver_location_logs', function (Blueprint $table) {
            // Drop unnecessary columns for tablet tracking
            $table->dropColumn([
                'accuracy',
                'speed',
                'heading',
                'source',
                'distance_from_previous',
                'time_difference_seconds',
                'estimated_speed_kmh',
                'is_suspicious',
            ]);

            // Add distance tracking columns
            $table->decimal('daily_distance_km', 8, 2)->default(0)->after('recorded_at');
            $table->decimal('monthly_distance_km', 10, 2)->default(0)->after('daily_distance_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_location_logs', function (Blueprint $table) {
            // Remove distance tracking columns
            $table->dropColumn(['daily_distance_km', 'monthly_distance_km']);

            // Add back the original columns
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->decimal('speed', 8, 2)->nullable();
            $table->decimal('heading', 8, 2)->nullable();
            $table->string('source')->nullable();
            $table->decimal('distance_from_previous', 10, 3)->nullable();
            $table->integer('time_difference_seconds')->nullable();
            $table->decimal('estimated_speed_kmh', 8, 2)->nullable();
            $table->boolean('is_suspicious')->default(false);
        });
    }
};
