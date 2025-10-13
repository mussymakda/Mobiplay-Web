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
            // Approval workflow fields
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('submitted_for_review_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('approval_history')->nullable();

            // Content moderation fields
            $table->boolean('content_flagged')->default(false);
            $table->json('content_flags')->nullable(); // Store specific content issues
            $table->integer('revision_count')->default(0);

            // Auto-approval fields
            $table->boolean('auto_approved')->default(false);
            $table->string('auto_approval_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'reviewed_by',
                'submitted_for_review_at',
                'reviewed_at',
                'admin_notes',
                'rejection_reason',
                'approval_history',
                'content_flagged',
                'content_flags',
                'revision_count',
                'auto_approved',
                'auto_approval_reason',
            ]);
        });
    }
};
