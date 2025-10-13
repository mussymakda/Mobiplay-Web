<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Driver;
use App\Models\Impression;
use App\Services\AdService;
use App\Services\LocationLoggingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    private AdService $adService;

    private LocationLoggingService $locationService;

    public function __construct(AdService $adService, LocationLoggingService $locationService)
    {
        $this->adService = $adService;
        $this->locationService = $locationService;

        // Increase execution timeout for API operations
        set_time_limit(config('app.timeouts.api', 300));
    }

    /**
     * Check if driver has recent impressions (within last hour)
     */
    private function hasRecentImpressions(Driver $driver): bool
    {
        return Impression::where('driver_id', $driver->id)
            ->where('created_at', '>=', now()->subHour())
            ->exists();
    }

    /**
     * Driver login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Validation failed', $validator->errors(), 422);
        }

        $driver = Driver::where('email', $request->email)->first();

        if (! $driver || ! Hash::check($request->password, $driver->password)) {
            return $this->apiResponse(false, 'Invalid credentials', null, 401);
        }

        if (! $driver->is_active) {
            return $this->apiResponse(false, 'Your account has been deactivated. Please contact support.', null, 403);
        }

        // Create API token
        $token = $driver->createToken('driver-app')->plainTextToken;

        return $this->apiResponse(true, 'Login successful', [
            'driver' => [
                'id' => $driver->id,
                'name' => $driver->name,
                'email' => $driver->email,
                'is_active' => $driver->is_active,
                'total_earnings' => number_format($driver->total_earnings, 2),
                'unpaid_amount' => number_format($driver->unpaid_amount, 2),
            ],
            'token' => $token,
        ]);
    }

    /**
     * Driver logout (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->apiResponse(true, 'Logged out successfully');
    }

    /**
     * Get driver profile information
     */
    public function profile(Request $request): JsonResponse
    {
        $driver = $request->user();

        return $this->apiResponse(true, 'Profile retrieved successfully', [
            'id' => $driver->id,
            'name' => $driver->name,
            'email' => $driver->email,
            'phone' => $driver->phone,
            'license_number' => $driver->license_number,
            'vehicle_number' => $driver->vehicle_number,
            'vehicle_type' => $driver->vehicle_type,
            'is_active' => $driver->is_active,
            'current_location' => [
                'latitude' => $driver->current_latitude,
                'longitude' => $driver->current_longitude,
                'last_update' => $driver->last_location_update,
            ],
            'earnings' => [
                'total' => number_format($driver->total_earnings, 2),
                'unpaid' => number_format($driver->unpaid_amount, 2),
            ],
        ]);
    }

    /**
     * Request ads based on driver location (Smart Algorithm)
     */
    public function requestAds(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Invalid location data', $validator->errors(), 422);
        }

        $driver = $request->user();

        if (! $driver->isAvailable()) {
            return $this->apiResponse(false, 'Driver must be online to receive ads', null, 403);
        }

        // Log driver location for tracking and analytics
        $this->locationService->logLocation(
            $driver,
            (float) $request->latitude,
            (float) $request->longitude,
            null,  // accuracy is null
            null,  // speed is null
            null,  // heading is null
            'ad_request'
        );

        // Get active ads to debug
        $activeAds = Ad::where('status', Ad::STATUS_ACTIVE)
            ->whereHas('package', function ($query) {
                $query->where('is_active', true);
            })
            ->where('budget', '>', DB::raw('spent'))
            ->get();

        // Get location-based ads using smart algorithm
        $ads = $this->adService->getAdsForDriver(
            $driver,
            (float) $request->latitude,
            (float) $request->longitude
        );

        if ($ads->isEmpty()) {
            // Check for recent impressions
            $recentImpressions = Impression::where('driver_id', $driver->id)
                ->where('created_at', '>=', now()->subHours(1))
                ->get();

            // Debug: Return diagnostic information
            return $this->apiResponse(true, 'No ads available for your current location', [
                'ads' => [],
                'message' => 'Move to a different area or check back later',
                'debug' => [
                    'driver_location' => [
                        'latitude' => (float) $request->latitude,
                        'longitude' => (float) $request->longitude,
                    ],
                    'active_ads_count' => $activeAds->count(),
                    'active_ads' => $activeAds->map(function ($ad) {
                        return [
                            'id' => $ad->id,
                            'campaign_name' => $ad->campaign_name,
                            'location' => [
                                'latitude' => $ad->latitude,
                                'longitude' => $ad->longitude,
                                'radius_miles' => $ad->radius_miles,
                            ],
                            'package_id' => $ad->package_id,
                            'budget' => $ad->budget,
                            'spent' => $ad->spent,
                        ];
                    }),
                    'recent_impressions' => $recentImpressions->map(function ($impression) {
                        return [
                            'ad_id' => $impression->ad_id,
                            'time' => $impression->created_at->diffForHumans(),
                        ];
                    }),
                ],
            ]);
        }

        // Format ads for display
        $formattedAds = $ads->take(5)->map(function ($ad) {
            return $this->adService->formatAdForDisplay($ad);
        });

        return $this->apiResponse(true, 'Ads retrieved successfully', [
            'ads' => $formattedAds,
            'total_available' => $ads->count(),
            'location' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ],
        ]);
    }

    /**
     * Record ad impression (view or interaction)
     */
    public function recordImpression(Request $request, Ad $ad): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:display,qr_scan',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Invalid impression type', $validator->errors(), 422);
        }

        $driver = $request->user();

        // Check if ad is still active and within budget
        if ($ad->status !== Ad::STATUS_ACTIVE || $ad->spent >= $ad->budget) {
            return $this->apiResponse(false, 'Ad is no longer available', null, 410);
        }

        // Record impression and calculate earnings
        $result = $this->adService->recordImpression($ad, $driver, $request->type);

        return $this->apiResponse(true, 'Impression recorded successfully', [
            'earnings' => $result,
            'ad_id' => $ad->id,
            'type' => $request->type,
        ]);
    }

    /**
     * Get driver earnings summary
     */
    public function getEarningsSummary(Request $request): JsonResponse
    {
        $driver = $request->user();

        // Get earnings stats
        $todayEarnings = Impression::where('driver_id', $driver->id)
            ->whereDate('created_at', now()->toDateString())
            ->sum('cost');

        $thisWeekEarnings = Impression::where('driver_id', $driver->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('cost');

        $thisMonthEarnings = Impression::where('driver_id', $driver->id)
            ->whereMonth('created_at', now()->month)
            ->sum('cost');

        return $this->apiResponse(true, 'Earnings summary retrieved', [
            'earnings' => [
                'total_earnings' => number_format($driver->total_earnings, 2),
                'unpaid_amount' => number_format($driver->unpaid_amount, 2),
                'today' => number_format($todayEarnings, 2),
                'this_week' => number_format($thisWeekEarnings, 2),
                'this_month' => number_format($thisMonthEarnings, 2),
            ],
            'statistics' => [
                'total_impressions' => Impression::where('driver_id', $driver->id)->count(),
                'qr_scans' => Impression::where('driver_id', $driver->id)
                    ->where('type', Impression::TYPE_QR_SCAN)->count(),
            ],
        ]);
    }

    /**
     * QR Code redirect with tracking
     */
    public function redirectQR(string $qrCode): JsonResponse
    {
        try {
            // Decode QR code data
            $decoded = base64_decode($qrCode);
            [$adId, $originalUrl] = explode('|', $decoded, 2);

            $ad = Ad::find($adId);
            if (! $ad) {
                return $this->apiResponse(false, 'Invalid QR code', null, 404);
            }

            // Find the driver who's currently showing this ad
            $recentImpression = Impression::where('ad_id', $ad->id)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->orderBy('created_at', 'desc')
                ->first();

            if ($recentImpression && $recentImpression->driver) {
                // Record QR scan impression
                $this->adService->recordImpression($ad, $recentImpression->driver, Impression::TYPE_QR_SCAN);
            }

            // Redirect to original URL
            return response()->json([
                'success' => true,
                'message' => 'QR scan recorded',
                'redirect_url' => $originalUrl ?: $ad->cta_url,
            ]);

        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Invalid QR code format', null, 400);
        }
    }

    /**
     * Standard API response format
     */
    /**
     * Update driver's current location
     */
    public function updateLocation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
            'speed' => 'nullable|numeric|min:0',
            'heading' => 'nullable|numeric|between:0,359',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Invalid location data', $validator->errors(), 422);
        }

        $driver = $request->user();

        // Log location with enhanced data
        $logData = $this->locationService->logLocation(
            $driver,
            (float) $request->latitude,
            (float) $request->longitude,
            $request->accuracy ? (float) $request->accuracy : null,
            $request->speed ? (float) $request->speed : null,
            $request->heading ? (float) $request->heading : null,
            'location_update'
        );

        // Update driver's current location in profile
        $driver->update([
            'current_latitude' => $request->latitude,
            'current_longitude' => $request->longitude,
            'last_location_update' => now(),
        ]);

        return $this->apiResponse(true, 'Location updated successfully', [
            'location' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_at' => now()->toISOString(),
            ],
            'analytics' => $logData ? [
                'distance_traveled' => $logData['distance_traveled'] ?? 0,
                'average_speed' => $logData['average_speed'] ?? 0,
            ] : null,
        ]);
    }

    /**
     * Get driver's location history
     */
    public function getLocationHistory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:100',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Invalid parameters', $validator->errors(), 422);
        }

        $driver = $request->user();
        $limit = $request->input('limit', 50);

        $query = $driver->locationLogs()->orderBy('created_at', 'desc');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $locations = $query->limit($limit)->get();

        return $this->apiResponse(true, 'Location history retrieved successfully', [
            'locations' => $locations->map(function ($log) {
                return [
                    'latitude' => $log->latitude,
                    'longitude' => $log->longitude,
                    'accuracy' => $log->accuracy,
                    'speed' => $log->speed,
                    'heading' => $log->heading,
                    'context' => $log->context,
                    'distance_traveled' => $log->formatted_distance,
                    'timestamp' => $log->created_at->toISOString(),
                ];
            }),
            'total_locations' => $locations->count(),
            'period' => [
                'from' => $request->date_from ?? 'all',
                'to' => $request->date_to ?? 'now',
            ],
        ]);
    }

    /**
     * Get movement analytics for the driver
     */
    public function getMovementAnalytics(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'period' => 'nullable|string|in:today,week,month,custom',
            'date_from' => 'nullable|date|required_if:period,custom',
            'date_to' => 'nullable|date|required_if:period,custom|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Invalid parameters', $validator->errors(), 422);
        }

        $driver = $request->user();
        $period = $request->input('period', 'today');

        // Calculate date range based on period
        switch ($period) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'custom':
                $startDate = $request->date_from ? Carbon::parse($request->date_from)->startOfDay() : now()->startOfDay();
                $endDate = $request->date_to ? Carbon::parse($request->date_to)->endOfDay() : now()->endOfDay();
                break;
            default:
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
        }

        // Get analytics using the location service
        $analytics = $this->locationService->getMovementAnalytics(
            $driver,
            $startDate,
            $endDate
        );

        return $this->apiResponse(true, 'Movement analytics retrieved successfully', [
            'analytics' => $analytics,
            'period' => $period,
        ]);
    }

    private function apiResponse(bool $success, string $message, $data = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
