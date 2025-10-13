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
            // Personal details
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('country')->nullable()->after('phone');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('postal_code')->nullable()->after('city');

            // Vehicle details
            $table->string('car_make')->nullable()->after('vehicle_type');
            $table->string('car_model')->nullable()->after('car_make');
            $table->string('car_year')->nullable()->after('car_model');
            $table->integer('trips_per_month')->nullable()->after('car_year');

            // Verification status
            $table->enum('verification_status', ['pending', 'under_review', 'verified', 'rejected'])->default('pending')->after('is_active');

            // Document uploads
            $table->string('uber_screenshot')->nullable()->after('verification_status');
            $table->string('identity_document')->nullable()->after('uber_screenshot');
            $table->string('vehicle_number_plate')->nullable()->after('identity_document');

            // Timestamps for verification process
            $table->timestamp('documents_uploaded_at')->nullable()->after('vehicle_number_plate');
            $table->timestamp('verified_at')->nullable()->after('documents_uploaded_at');
            $table->text('rejection_reason')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'country',
                'state',
                'city',
                'postal_code',
                'car_make',
                'car_model',
                'car_year',
                'trips_per_month',
                'verification_status',
                'uber_screenshot',
                'identity_document',
                'vehicle_number_plate',
                'documents_uploaded_at',
                'verified_at',
                'rejection_reason',
            ]);
        });
    }
};
