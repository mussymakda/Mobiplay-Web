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
        Schema::table('payments', function (Blueprint $table) {
            // Modify the status enum to include 'processing'
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove 'processing' from the enum
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending'");
        });
    }
};
