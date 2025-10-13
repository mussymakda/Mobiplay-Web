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
        Schema::table('ads', function (Blueprint $table) {
            // Modify the status enum to include 'draft'
            $table->enum('status', ['draft', 'pending', 'active', 'paused', 'completed', 'rejected'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Revert back to original enum without 'draft'
            $table->enum('status', ['pending', 'active', 'paused', 'completed', 'rejected'])->default('pending')->change();
        });
    }
};
