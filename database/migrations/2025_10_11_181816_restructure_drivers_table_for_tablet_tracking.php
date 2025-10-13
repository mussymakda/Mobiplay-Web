<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add device_id column without unique constraint and populate it
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('name');
            $table->decimal('daily_distance_km', 8, 2)->default(0)->after('current_longitude');
        });

        // Populate device_id with unique values for existing records
        DB::statement("UPDATE drivers SET device_id = CONCAT('tablet_', id) WHERE device_id IS NULL OR device_id = ''");

        // Now make device_id unique and not nullable
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('device_id')->unique()->nullable(false)->change();
        });

        // Remove driver management related columns
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'password',
                'phone',
                'license_number',
                'car_make',
                'car_model',
                'car_year',
                'first_name',
                'last_name',
                'country',
                'state',
                'city',
                'postal_code',
                'vehicle_type',
                'verification_status',
                'uber_screenshot',
                'identity_document',
                'vehicle_number_plate',
                'total_earnings',
                'unpaid_amount',
                'trips_per_month',
                'documents_uploaded_at',
                'verified_at',
                'email_verified_at',
                'rejection_reason',
                'remember_token',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Remove tablet tracking columns
            $table->dropColumn(['device_id', 'daily_distance_km']);

            // Restore driver management columns
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->string('password')->after('email_verified_at');
            $table->string('phone')->nullable()->after('password');
            $table->string('license_number')->nullable()->after('phone');
            $table->string('first_name')->nullable()->after('license_number');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('country')->nullable()->after('last_name');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('postal_code')->nullable()->after('city');
            $table->string('car_make')->nullable()->after('postal_code');
            $table->string('car_model')->nullable()->after('car_make');
            $table->string('car_year')->nullable()->after('car_model');
            $table->enum('vehicle_type', ['sedan', 'suv', 'hatchback', 'uber', 'taxi'])->default('sedan')->after('car_year');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending')->after('vehicle_type');
            $table->string('uber_screenshot')->nullable()->after('verification_status');
            $table->string('identity_document')->nullable()->after('uber_screenshot');
            $table->string('vehicle_number_plate')->nullable()->after('identity_document');
            $table->decimal('total_earnings', 10, 2)->default(0)->after('is_active');
            $table->decimal('unpaid_amount', 10, 2)->default(0)->after('total_earnings');
            $table->integer('trips_per_month')->default(0)->after('unpaid_amount');
            $table->timestamp('documents_uploaded_at')->nullable()->after('trips_per_month');
            $table->timestamp('verified_at')->nullable()->after('documents_uploaded_at');
            $table->text('rejection_reason')->nullable()->after('verified_at');
            $table->string('remember_token')->nullable()->after('rejection_reason');
        });
    }
};
