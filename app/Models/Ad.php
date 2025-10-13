<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'campaign_name',
        'media_type',
        'media_path',
        'cta_url',
        'cta_text',
        'qr_code_url',
        'qr_position',
        'latitude',
        'longitude',
        'location_name',
        'radius_miles',
        'status',
        'budget',
        'spent',
        'daily_budget',
        'daily_spent',
        'last_reset_date',
        'impressions',
        'qr_scans',
        'scheduled_date',
        // Approval fields
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
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'budget' => 'decimal:2',
            'spent' => 'decimal:2',
            'daily_budget' => 'decimal:2',
            'daily_spent' => 'decimal:2',
            'last_reset_date' => 'date',
            'impressions' => 'integer',
            'qr_scans' => 'integer',
            'radius_miles' => 'decimal:2',
            'scheduled_date' => 'date',
            // Approval field casts
            'submitted_for_review_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'approval_history' => 'array',
            'content_flags' => 'array',
            'content_flagged' => 'boolean',
            'auto_approved' => 'boolean',
            'revision_count' => 'integer',
        ];
    }

    // Status constants
    const STATUS_DRAFT = 'draft';

    const STATUS_PENDING = 'pending';

    const STATUS_ACTIVE = 'active';

    const STATUS_PAUSED = 'paused';

    const STATUS_COMPLETED = 'completed';

    const STATUS_REJECTED = 'rejected';

    // Media type constants
    const MEDIA_TYPE_IMAGE = 'image';

    const MEDIA_TYPE_VIDEO = 'video';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function impressions()
    {
        return $this->hasMany(Impression::class);
    }

    /**
     * Get the admin who reviewed this ad.
     */
    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function getQrScanRateAttribute()
    {
        return $this->impressions > 0 ? round(($this->qr_scans / $this->impressions) * 100, 2) : 0;
    }

    public function getCostPerQrScanAttribute()
    {
        return $this->qr_scans > 0 ? round($this->spent / $this->qr_scans, 2) : 0;
    }

    public function getCostPerImpressionAttribute()
    {
        return $this->impressions > 0 ? round($this->spent / $this->impressions, 4) : 0;
    }

    public function getRemainingBudgetAttribute()
    {
        return $this->budget - $this->spent;
    }

    // Approval workflow methods

    /**
     * Submit ad for review.
     */
    public function submitForReview()
    {
        $this->update([
            'status' => self::STATUS_PENDING,
            'submitted_for_review_at' => now(),
        ]);

        $this->addToApprovalHistory('submitted', 'Ad submitted for review');
    }

    /**
     * Approve the ad.
     */
    public function approve(Admin $admin, ?string $notes = null)
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
            'rejection_reason' => null,
        ]);

        $this->addToApprovalHistory('approved', $notes ?? 'Ad approved', $admin);
    }

    /**
     * Reject the ad.
     */
    public function reject(Admin $admin, string $reason, ?string $notes = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $notes,
            'revision_count' => $this->revision_count + 1,
        ]);

        $this->addToApprovalHistory('rejected', $reason, $admin, $notes);
    }

    /**
     * Flag content for moderation.
     */
    public function flagContent(array $flags, Admin $admin, ?string $notes = null)
    {
        $this->update([
            'content_flagged' => true,
            'content_flags' => $flags,
            'admin_notes' => $notes,
        ]);

        $this->addToApprovalHistory('content_flagged', 'Content flagged: '.implode(', ', $flags), $admin, $notes);
    }

    /**
     * Auto-approve the ad.
     */
    public function autoApprove(string $reason = 'Meets auto-approval criteria')
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'auto_approved' => true,
            'auto_approval_reason' => $reason,
            'reviewed_at' => now(),
        ]);

        $this->addToApprovalHistory('auto_approved', $reason);
    }

    /**
     * Add entry to approval history.
     */
    protected function addToApprovalHistory(string $action, string $details, ?Admin $admin = null, ?string $notes = null)
    {
        $history = $this->approval_history ?? [];

        $history[] = [
            'action' => $action,
            'details' => $details,
            'admin_id' => $admin?->id,
            'admin_name' => $admin?->name,
            'notes' => $notes,
            'timestamp' => now()->toISOString(),
        ];

        $this->update(['approval_history' => $history]);
    }

    /**
     * Check if ad is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if ad is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if ad is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if ad has content flags.
     */
    public function isContentFlagged(): bool
    {
        return $this->content_flagged;
    }

    /**
     * Get the approval status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_PENDING => 'warning',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_DRAFT => 'gray',
            self::STATUS_PAUSED => 'info',
            self::STATUS_COMPLETED => 'primary',
            default => 'gray',
        };
    }

    public function getRemainingDailyBudgetAttribute()
    {
        $this->resetDailySpentIfNeeded();

        return $this->daily_budget - $this->daily_spent;
    }

    public function canAffordDailySpend($amount)
    {
        $this->resetDailySpentIfNeeded();

        return ($this->daily_spent + $amount) <= $this->daily_budget;
    }

    public function resetDailySpentIfNeeded()
    {
        $mexicoToday = now('America/Mexico_City')->toDateString();

        if (is_null($this->last_reset_date) || $this->last_reset_date->toDateString() !== $mexicoToday) {
            $this->update([
                'daily_spent' => 0,
                'last_reset_date' => $mexicoToday,
            ]);
        }
    }

    public function getIsActiveAttribute()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByLocation($query, $latitude, $longitude, $radiusKm = 50)
    {
        return $query->whereRaw(
            '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= radius_km',
            [$latitude, $longitude, $latitude]
        );
    }
}
