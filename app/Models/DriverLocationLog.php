<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverLocationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'latitude',
        'longitude',
        'recorded_at',
        'daily_distance_km',
        'monthly_distance_km',
        'metadata',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'daily_distance_km' => 'decimal:2',
        'monthly_distance_km' => 'decimal:2',
        'metadata' => 'array',
        'recorded_at' => 'datetime',
    ];

    /**
     * Get the driver that this location log belongs to
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Scope to get recent locations
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('recorded_at', '>=', Carbon::now()->subHours($hours));
    }

    /**
     * Scope to get suspicious locations only
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    /**
     * Scope to get locations within date range
     */
    public function scopeDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get locations within geographic bounds
     */
    public function scopeWithinBounds($query, float $minLat, float $maxLat, float $minLon, float $maxLon)
    {
        return $query->where('latitude', '>=', $minLat)
            ->where('latitude', '<=', $maxLat)
            ->where('longitude', '>=', $minLon)
            ->where('longitude', '<=', $maxLon);
    }

    /**
     * Get formatted location string
     */
    public function getLocationStringAttribute(): string
    {
        return number_format($this->latitude, 6).', '.number_format($this->longitude, 6);
    }

    /**
     * Get human-readable time since recorded
     */
    public function getTimeSinceRecordedAttribute(): string
    {
        return $this->recorded_at->diffForHumans();
    }

    /**
     * Check if location is considered stale (older than specified minutes)
     */
    public function isStale(int $minutes = 30): bool
    {
        return $this->recorded_at < Carbon::now()->subMinutes($minutes);
    }

    /**
     * Get distance formatted in appropriate units
     */
    public function getFormattedDistanceAttribute(): ?string
    {
        if (! $this->distance_from_previous) {
            return null;
        }

        $distance = $this->distance_from_previous;

        if ($distance < 1000) {
            return number_format($distance, 1).' m';
        } else {
            return number_format($distance / 1000, 2).' km';
        }
    }

    /**
     * Get speed formatted with units
     */
    public function getFormattedSpeedAttribute(): ?string
    {
        if (! $this->estimated_speed_kmh) {
            return null;
        }

        return number_format($this->estimated_speed_kmh, 1).' km/h';
    }
}
