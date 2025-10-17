<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'status',
        'stripe_payment_id',
        'stripe_customer_id',
        'transaction_id',
        'offer_id',
        'bonus_amount',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Payment types
    const TYPE_DEPOSIT = 'deposit';

    const TYPE_AUTO_DEBIT = 'auto_debit';

    const TYPE_BONUS = 'bonus';

    const TYPE_AD_SPEND = 'ad_spend';

    const TYPE_REFUND = 'refund';

    // Payment statuses
    const STATUS_PENDING = 'pending';

    const STATUS_PROCESSING = 'processing';

    const STATUS_COMPLETED = 'completed';

    const STATUS_FAILED = 'failed';

    const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeDeposits($query)
    {
        return $query->where('type', self::TYPE_DEPOSIT);
    }

    public function scopeAdSpend($query)
    {
        return $query->where('type', self::TYPE_AD_SPEND);
    }

    // Get formatted amount
    public function getFormattedAmountAttribute()
    {
        return '$'.number_format($this->amount, 2);
    }

    // Get total amount including bonus
    public function getTotalAmountAttribute()
    {
        return $this->amount + ($this->bonus_amount ?? 0);
    }
}
