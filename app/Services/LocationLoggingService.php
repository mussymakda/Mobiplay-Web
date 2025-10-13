<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\DriverLocationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LocationLoggingService
{
    /**
     * Log driver location with detailed tracking information
     */
    public function logLocation(
        Driver $driver,
        float $latitude,
        float $longitude,
        ?float $accuracy = null,
        ?float $speed = null,
        ?float $heading = null,
        ?string $source = 'app'
    ): DriverLocationLog {
        // Validate coordinates
        $this->validateCoordinates($latitude, $longitude);

        // Calculate distance from previous location if exists
        $previousLocation = $this->getLastLocation($driver);
        $distanceFromPrevious = null;
        $timeDifference = null;
        $estimatedSpeed = null;

        if ($previousLocation) {
            $distanceFromPrevious = $this->calculateDistance(
                $previousLocation->latitude,
                $previousLocation->longitude,
                $latitude,
                $longitude
            );

            $timeDifference = Carbon::now()->diffInSeconds($previousLocation->recorded_at);

            // Calculate estimated speed (km/h) if time difference > 0
            if ($timeDifference > 0) {
                $estimatedSpeed = ($distanceFromPrevious / 1000) / ($timeDifference / 3600);
            }
        }

        // Detect suspicious location changes
        $isSuspicious = $this->detectSuspiciousMovement(
            $distanceFromPrevious,
            $timeDifference,
            $estimatedSpeed
        );

        // Create location log entry
        $locationLog = DriverLocationLog::create([
            'driver_id' => $driver->id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'accuracy' => $accuracy,
            'speed' => $speed,
            'heading' => $heading,
            'source' => $source,
            'recorded_at' => Carbon::now(),
            'distance_from_previous' => $distanceFromPrevious,
            'time_difference_seconds' => $timeDifference,
            'estimated_speed_kmh' => $estimatedSpeed,
            'is_suspicious' => $isSuspicious,
            'metadata' => [
                'user_agent' => request()->userAgent(),
                'ip_address' => request()->ip(),
                'session_id' => session()->getId(),
            ],
        ]);

        // Update driver's current location
        $driver->updateLocation($latitude, $longitude);

        // Log suspicious activity if detected
        if ($isSuspicious) {
            Log::warning('Suspicious driver location detected', [
                'driver_id' => $driver->id,
                'location_log_id' => $locationLog->id,
                'distance_km' => $distanceFromPrevious ? round($distanceFromPrevious / 1000, 2) : null,
                'time_seconds' => $timeDifference,
                'estimated_speed_kmh' => $estimatedSpeed ? round($estimatedSpeed, 2) : null,
            ]);
        }

        return $locationLog;
    }

    /**
     * Get driver's location history with filtering options
     */
    public function getLocationHistory(
        Driver $driver,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
        int $limit = 100,
        bool $suspiciousOnly = false
    ) {
        $query = DriverLocationLog::where('driver_id', $driver->id);

        if ($startDate) {
            $query->where('recorded_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('recorded_at', '<=', $endDate);
        }

        if ($suspiciousOnly) {
            $query->where('is_suspicious', true);
        }

        return $query->orderBy('recorded_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get driver's movement analytics for a time period
     */
    public function getMovementAnalytics(Driver $driver, Carbon $startDate, Carbon $endDate): array
    {
        $locations = DriverLocationLog::where('driver_id', $driver->id)
            ->whereBetween('recorded_at', [$startDate, $endDate])
            ->orderBy('recorded_at', 'asc')
            ->get();

        if ($locations->isEmpty()) {
            return [
                'total_distance_km' => 0,
                'average_speed_kmh' => 0,
                'max_speed_kmh' => 0,
                'total_time_hours' => 0,
                'location_updates' => 0,
                'suspicious_activities' => 0,
            ];
        }

        $totalDistance = $locations->sum('distance_from_previous') ?? 0;
        $totalTime = $endDate->diffInHours($startDate);
        $speeds = $locations->whereNotNull('estimated_speed_kmh')->pluck('estimated_speed_kmh');
        $suspiciousCount = $locations->where('is_suspicious', true)->count();

        return [
            'total_distance_km' => round($totalDistance / 1000, 2),
            'average_speed_kmh' => $speeds->isEmpty() ? 0 : round($speeds->average(), 2),
            'max_speed_kmh' => $speeds->isEmpty() ? 0 : round($speeds->max(), 2),
            'total_time_hours' => round($totalTime, 2),
            'location_updates' => $locations->count(),
            'suspicious_activities' => $suspiciousCount,
            'accuracy_average' => round($locations->whereNotNull('accuracy')->avg('accuracy'), 2),
        ];
    }

    /**
     * Get driver's current location with last update info
     */
    public function getCurrentLocationInfo(Driver $driver): array
    {
        $lastLocation = $this->getLastLocation($driver);

        return [
            'current_location' => [
                'latitude' => $driver->current_latitude,
                'longitude' => $driver->current_longitude,
                'last_update' => $driver->last_location_update,
            ],
            'last_log' => $lastLocation ? [
                'id' => $lastLocation->id,
                'recorded_at' => $lastLocation->recorded_at,
                'accuracy' => $lastLocation->accuracy,
                'speed' => $lastLocation->speed,
                'source' => $lastLocation->source,
                'is_suspicious' => $lastLocation->is_suspicious,
            ] : null,
            'location_freshness' => $driver->last_location_update
                ? Carbon::now()->diffInMinutes($driver->last_location_update)
                : null,
        ];
    }

    /**
     * Validate GPS coordinates
     */
    private function validateCoordinates(float $latitude, float $longitude): void
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new \InvalidArgumentException('Invalid latitude: must be between -90 and 90');
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new \InvalidArgumentException('Invalid longitude: must be between -180 and 180');
        }

        // Check for obviously fake coordinates (0,0) unless actually at that location
        if ($latitude == 0 && $longitude == 0) {
            throw new \InvalidArgumentException('Invalid coordinates: (0,0) is likely a GPS error');
        }
    }

    /**
     * Get last recorded location for driver
     */
    private function getLastLocation(Driver $driver): ?DriverLocationLog
    {
        return DriverLocationLog::where('driver_id', $driver->id)
            ->orderBy('recorded_at', 'desc')
            ->first();
    }

    /**
     * Calculate distance between two coordinates in meters using Haversine formula
     */
    private function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000; // Earth's radius in meters

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLatRad = deg2rad($lat2 - $lat1);
        $deltaLonRad = deg2rad($lon2 - $lon1);

        $a = sin($deltaLatRad / 2) * sin($deltaLatRad / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLonRad / 2) * sin($deltaLonRad / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in meters
    }

    /**
     * Detect suspicious movement patterns
     */
    private function detectSuspiciousMovement(
        ?float $distance,
        ?int $timeDifference,
        ?float $estimatedSpeed
    ): bool {
        if (! $distance || ! $timeDifference || ! $estimatedSpeed) {
            return false;
        }

        // Flags for suspicious activity
        $flags = [];

        // Speed too high (over 200 km/h)
        if ($estimatedSpeed > 200) {
            $flags[] = 'excessive_speed';
        }

        // Large distance in short time (teleportation detection)
        if ($distance > 1000 && $timeDifference < 60) { // 1km in under 1 minute
            $flags[] = 'teleportation';
        }

        // Too frequent updates (every few seconds)
        if ($timeDifference < 5) {
            $flags[] = 'too_frequent';
        }

        return ! empty($flags);
    }

    /**
     * Clean old location logs (keep only last 30 days by default)
     */
    public function cleanOldLogs(int $daysToKeep = 30): int
    {
        $cutoffDate = Carbon::now()->subDays($daysToKeep);

        return DriverLocationLog::where('recorded_at', '<', $cutoffDate)->delete();
    }

    /**
     * Get location summary for admin dashboard
     */
    public function getLocationSummary(?Carbon $date = null): array
    {
        $date = $date ?? Carbon::today();

        return [
            'active_drivers' => DriverLocationLog::whereDate('recorded_at', $date)
                ->distinct('driver_id')
                ->count(),
            'total_location_updates' => DriverLocationLog::whereDate('recorded_at', $date)->count(),
            'suspicious_activities' => DriverLocationLog::whereDate('recorded_at', $date)
                ->where('is_suspicious', true)
                ->count(),
            'average_accuracy' => round(
                DriverLocationLog::whereDate('recorded_at', $date)
                    ->whereNotNull('accuracy')
                    ->avg('accuracy'), 2
            ),
        ];
    }
}
