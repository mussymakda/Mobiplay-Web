<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Impression extends Model
{
    const TYPE_DISPLAY = 'display';

    const TYPE_QR_SCAN = 'qr_scan';

    protected $fillable = [
        'ad_id',
        'user_id',
        'driver_id',
        'type',
        'ip_address',
        'user_agent',
        'location_data',
        'device_info',
        'cost',
        'viewed_at',
    ];

    protected $casts = [
        'location_data' => 'array',
        'device_info' => 'array',
        'cost' => 'decimal:4',
        'viewed_at' => 'datetime',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_DISPLAY => 'Display',
            self::TYPE_QR_SCAN => 'QR Scan',
        ];
    }
}
