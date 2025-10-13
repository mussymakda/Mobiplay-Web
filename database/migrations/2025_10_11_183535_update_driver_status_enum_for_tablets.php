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
        // First, add the new enum values alongside existing ones
        DB::statement("ALTER TABLE drivers MODIFY COLUMN status ENUM('offline', 'online', 'busy', 'suspended', 'available') NOT NULL DEFAULT 'offline'");

        // Now update existing values to new ones
        DB::statement("UPDATE drivers SET status = 'available' WHERE status = 'online'");
        DB::statement("UPDATE drivers SET status = 'offline' WHERE status = 'suspended'");

        // Finally, restrict to only new enum values
        DB::statement("ALTER TABLE drivers MODIFY COLUMN status ENUM('available', 'busy', 'offline') NOT NULL DEFAULT 'offline'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update values back to old enum
        DB::statement("UPDATE drivers SET status = 'online' WHERE status = 'available'");

        // Restore old enum values
        DB::statement("ALTER TABLE drivers MODIFY COLUMN status ENUM('offline', 'online', 'busy', 'suspended') NOT NULL DEFAULT 'offline'");
    }
};
