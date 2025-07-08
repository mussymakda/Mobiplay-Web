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
        'qr_code_url',
        'latitude',
        'longitude',
        'location_name',
        'radius_miles',
        'status',
        'budget',
        'spent',
        'impressions',
        'qr_scans',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'budget' => 'decimal:2',
        'spent' => 'decimal:2',
        'impressions' => 'integer',
        'qr_scans' => 'integer',
        'radius_miles' => 'decimal:2',
    ];

    // Status constants
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
