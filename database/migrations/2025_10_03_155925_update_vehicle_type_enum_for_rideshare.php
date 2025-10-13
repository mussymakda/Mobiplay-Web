<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the vehicle_type enum to support rideshare vehicle types
        DB::statement("ALTER TABLE drivers MODIFY COLUMN vehicle_type ENUM('sedan', 'suv', 'hatchback', 'minivan') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original vehicle types
        DB::statement("ALTER TABLE drivers MODIFY COLUMN vehicle_type ENUM('car', 'truck', 'motorcycle', 'van') NOT NULL");
    }
};
