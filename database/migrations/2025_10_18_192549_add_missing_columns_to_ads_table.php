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
            // Add columns that might be missing based on the Ad model fillable array
            if (!Schema::hasColumn('ads', 'cta_text')) {
                $table->string('cta_text')->nullable()->after('cta_url');
            }
            if (!Schema::hasColumn('ads', 'qr_code_url')) {
                $table->string('qr_code_url')->nullable()->after('cta_text');
            }
            if (!Schema::hasColumn('ads', 'qr_position')) {
                $table->string('qr_position')->nullable()->after('qr_code_url');
            }
            if (!Schema::hasColumn('ads', 'location_name')) {
                $table->string('location_name')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('ads', 'daily_budget')) {
                $table->decimal('daily_budget', 10, 2)->default(0)->after('budget');
            }
            if (!Schema::hasColumn('ads', 'daily_spent')) {
                $table->decimal('daily_spent', 10, 2)->default(0)->after('daily_budget');
            }
            if (!Schema::hasColumn('ads', 'last_reset_date')) {
                $table->date('last_reset_date')->nullable()->after('daily_spent');
            }
            
            // Approval fields
            if (!Schema::hasColumn('ads', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('scheduled_date');
            }
            if (!Schema::hasColumn('ads', 'submitted_for_review_at')) {
                $table->timestamp('submitted_for_review_at')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('ads', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('submitted_for_review_at');
            }
            if (!Schema::hasColumn('ads', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('reviewed_at');
            }
            if (!Schema::hasColumn('ads', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('ads', 'approval_history')) {
                $table->json('approval_history')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('ads', 'content_flagged')) {
                $table->boolean('content_flagged')->default(false)->after('approval_history');
            }
            if (!Schema::hasColumn('ads', 'content_flags')) {
                $table->json('content_flags')->nullable()->after('content_flagged');
            }
            if (!Schema::hasColumn('ads', 'revision_count')) {
                $table->integer('revision_count')->default(0)->after('content_flags');
            }
            if (!Schema::hasColumn('ads', 'auto_approved')) {
                $table->boolean('auto_approved')->default(false)->after('revision_count');
            }
            if (!Schema::hasColumn('ads', 'auto_approval_reason')) {
                $table->string('auto_approval_reason')->nullable()->after('auto_approved');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $columnsToRemove = [
                'cta_text', 'qr_code_url', 'qr_position', 'location_name',
                'daily_budget', 'daily_spent', 'last_reset_date',
                'reviewed_by', 'submitted_for_review_at', 'reviewed_at',
                'admin_notes', 'rejection_reason', 'approval_history',
                'content_flagged', 'content_flags', 'revision_count',
                'auto_approved', 'auto_approval_reason'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('ads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
