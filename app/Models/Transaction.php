<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    const TYPE_DEPOSIT = 'deposit';

    const TYPE_AD_SPEND = 'ad_spend';

    const TYPE_BONUS = 'bonus';

    const TYPE_REFUND = 'refund';

    const TYPE_DRIVER_EARNING = 'driver_earning';

    const TYPE_DRIVER_PAYOUT = 'driver_payout';

    const STATUS_PENDING = 'pending';

    const STATUS_COMPLETED = 'completed';

    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'driver_id',
        'ad_id',
        'type',
        'amount',
        'status',
        'reference',
        'metadata',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_DEPOSIT => 'Deposit',
            self::TYPE_AD_SPEND => 'Ad Spend',
            self::TYPE_BONUS => 'Bonus',
            self::TYPE_REFUND => 'Refund',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
        ];
    }
}
