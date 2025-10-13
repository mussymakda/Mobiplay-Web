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
        Schema::table('drivers', function (Blueprint $table) {
            // Remove status column - tablet tracking doesn't need status management
            if (Schema::hasColumn('drivers', 'status')) {
                $table->dropColumn('status');
            }

            // Remove verification-related columns if they exist
            $verificationColumns = [
                'documents_uploaded_at',
                'verified_at',
                'uber_screenshot',
                'identity_document',
                'vehicle_number_plate',
                'verification_status',
                'admin_notes',
            ];

            foreach ($verificationColumns as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Restore status column
            $table->enum('status', ['available', 'busy', 'offline'])->default('offline');

            // Restore verification columns
            $table->timestamp('documents_uploaded_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('uber_screenshot')->nullable();
            $table->string('identity_document')->nullable();
            $table->string('vehicle_number_plate')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
        });
    }
};
