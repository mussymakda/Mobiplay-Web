<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'license_number',
        'device_id',
        'vehicle_number',
        'uber_screenshot',
        'identity_document',
        'vehicle_number_plate',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'daily_distance_km',
        'is_active',
        'documents_uploaded_at',
        'verified_at',
        'verification_status',
        'admin_notes',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'daily_distance_km' => 'decimal:2',
        'is_active' => 'boolean',
        'last_location_update' => 'datetime',
        'documents_uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationships
     */
    public function impressions()
    {
        return $this->hasMany(Impression::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function locationLogs()
    {
        return $this->hasMany(DriverLocationLog::class);
    }

    /**
     * Check if tablet/driver is currently available for ads
     */
    public function isAvailable(): bool
    {
        return $this->is_active;
    }

    /**
     * Update tablet location
     */
    public function updateLocation(float $latitude, float $longitude): void
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'last_location_update' => now(),
        ]);
    }

    /**
     * Check if driver is verified
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'approved';
    }

    /**
     * Get verification status badge information
     */
    public function getVerificationStatusBadge(): array
    {
        return match ($this->verification_status) {
            'pending' => ['class' => 'badge-warning', 'text' => 'Pending Review'],
            'under_review' => ['class' => 'badge-info', 'text' => 'Under Review'],
            'approved' => ['class' => 'badge-success', 'text' => 'Approved'],
            'rejected' => ['class' => 'badge-danger', 'text' => 'Rejected'],
            default => ['class' => 'badge-secondary', 'text' => 'Unknown'],
        };
    }
}
