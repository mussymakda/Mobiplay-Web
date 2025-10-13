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
        // Add 'draft' to the existing status enum
        DB::statement("ALTER TABLE ads MODIFY COLUMN status ENUM('draft', 'pending', 'active', 'paused', 'completed', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'draft' from the status enum
        DB::statement("ALTER TABLE ads MODIFY COLUMN status ENUM('pending', 'active', 'paused', 'completed', 'rejected') DEFAULT 'pending'");
    }
};
