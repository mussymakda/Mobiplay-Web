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
            $table->boolean('email_verified')->default(false)->after('email');
            $table->string('phone_verification_code')->nullable()->after('phone');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_verification_code');
            $table->string('document_verification_status')->default('pending')->after('phone_verified_at');
            $table->text('document_verification_notes')->nullable()->after('document_verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'email_verified',
                'phone_verification_code',
                'phone_verified_at',
                'document_verification_status',
                'document_verification_notes',
            ]);
        });
    }
};
