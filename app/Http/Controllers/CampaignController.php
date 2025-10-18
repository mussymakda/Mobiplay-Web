<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    /**
     * Show the campaign wizard form.
     */
    public function showCampaignWizard()
    {
        try {
            // Fetch all active packages from the database
            $packages = Package::active()->orderBy('priority_level')->get();

            return view('campaign-wizard', compact('packages'));
        } catch (\Exception $e) {
            // If there's an error fetching packages, return view with empty collection
            $packages = collect();

            return view('campaign-wizard', compact('packages'));
        }
    }

    /**
     * Show the campaign list.
     */
    public function showCampaignList()
    {
        $user = Auth::user();

        // Get user's campaigns/ads
        $campaigns = Ad::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Separate campaigns by status for the view
        $publishedCampaigns = $campaigns->whereIn('status', ['active', 'completed', 'paused']);
        $draftCampaigns = $campaigns->where('status', 'draft');

        return view('camplain-list', compact('campaigns', 'publishedCampaigns', 'draftCampaigns'));
    }

    /**
     * Display a listing of the campaigns.
     * This is an alias for showCampaignList to maintain route compatibility.
     */
    public function index()
    {
        return $this->showCampaignList();
    }

    /**
     * Store a new campaign.
     */
    public function store(Request $request)
    {
        // Check if packages are available in the database
        $hasPackages = Package::active()->count() > 0;

        $rules = [
            'campaign_name' => 'required|string|max:255',
            'ctaUrl' => 'nullable|url',
            'media_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,mpeg|max:51200', // 50MB max
            'selected_latitude' => 'nullable|numeric',
            'selected_longitude' => 'nullable|numeric',
            'selected_radius' => 'nullable|numeric|min:1|max:50',
        ];

        // Only require package selection if packages exist in database
        if ($hasPackages) {
            $rules['selected_package_id'] = 'required|exists:packages,id';
        }

        $request->validate($rules);

        // Handle file upload
        $mediaPath = null;
        if ($request->hasFile('media_file')) {
            $mediaPath = $request->file('media_file')->store('campaign_media', 'public');
        }

        // Here you would typically save the campaign to the database
        // For now, we'll just redirect with a success message

        return redirect()->route('camplain-list')->with('success', 'Campaign created successfully! Media saved to: '.$mediaPath);
    }

    /**
     * Create a new ad campaign.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Debug: Log the request data
        Log::info('Campaign creation attempt:', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'has_file' => $request->hasFile('media_file'),
        ]);

        // Check if packages are available in the database
        $hasPackages = Package::active()->count() > 0;

        $rules = [
            'campaign_name' => 'required|string|max:255',
            'ctaUrl' => 'nullable|url',
            'media_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,mpeg|max:51200', // 50MB max
            'selected_latitude' => 'nullable|numeric',
            'selected_longitude' => 'nullable|numeric',
            'selected_radius' => 'nullable|numeric|min:1|max:50',
            'daily_budget' => 'required|numeric|min:1|max:1000',
            'scheduled_date' => 'nullable|date',
            'save_as_draft' => 'nullable|boolean',
        ];

        // Only require package selection if packages exist in database
        if ($hasPackages) {
            $rules['selected_package_id'] = 'required|exists:packages,id';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Handle file upload
            $mediaPath = null;
            $mediaType = null;
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $mediaPath = $file->store('campaign_media', 'public');

                // Determine media type
                $mimeType = $file->getMimeType();
                $mediaType = str_starts_with($mimeType, 'video/') ? Ad::MEDIA_TYPE_VIDEO : Ad::MEDIA_TYPE_IMAGE;
            }

            // Get package if selected and calculate budget
            $package = null;
            $dailyBudget = $request->daily_budget ?? 1.00;
            $budget = $dailyBudget; // Use daily budget as the initial budget

            if ($hasPackages && $request->selected_package_id) {
                $package = Package::findOrFail($request->selected_package_id);
                // Budget is now based on user input, not package price
            }

            // Determine status
            $isDraft = $request->boolean('save_as_draft');
            $status = $isDraft ? Ad::STATUS_DRAFT : Ad::STATUS_ACTIVE;

            // Create the ad
            $ad = Ad::create([
                'user_id' => $user->id,
                'package_id' => $package ? $package->id : null,
                'campaign_name' => $request->campaign_name,
                'media_type' => $mediaType,
                'media_path' => $mediaPath,
                'cta_url' => $request->ctaUrl,
                'latitude' => $request->selected_latitude,
                'longitude' => $request->selected_longitude,
                'radius_miles' => $request->selected_radius ? round($request->selected_radius * 0.621371, 2) : null, // Convert km to miles
                'status' => $status,
                'budget' => $budget,
                'spent' => 0,
                'impressions' => 0,
                'qr_scans' => 0,
                'scheduled_date' => $request->scheduled_date ?: now()->format('Y-m-d'), // Use provided date or today
            ]);

            // If not a draft, check balance and handle payment
            if (! $isDraft && $budget > 0) {
                // Check if user has sufficient balance
                if ($user->total_balance < $budget) {
                    DB::commit();

                    // Redirect to payment with ad info
                    return redirect()->route('payment.make', [
                        'ad_id' => $ad->id,
                        'amount' => $budget,
                        'description' => "Payment for campaign: {$ad->campaign_name}",
                    ])->with('info', 'Insufficient balance. Please add funds to activate your campaign.');
                }

                // Deduct from user balance
                $user->deductBalance($budget, 'ad_spend', "Campaign budget for: {$ad->campaign_name}");

                // Update ad as active since payment is handled
                $ad->update(['status' => Ad::STATUS_ACTIVE]);
            }

            DB::commit();

            $message = $isDraft
                ? 'Campaign saved as draft successfully!'
                : ($budget > 0 ? 'Campaign created and activated successfully!' : 'Campaign created successfully!');

            return redirect()->route('camplain-list')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Campaign creation failed:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'error' => 'Failed to create campaign: '.$e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Get nearby drivers for campaign creation/editing maps
     */
    public function getNearbyDrivers(Request $request)
    {
        // Validate request parameters
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:1|max:100',
        ]);

        try {
            // Get parameters
            $latitude = (float) $request->latitude;
            $longitude = (float) $request->longitude;
            $radius = (float) $request->radius;

            // Fallback to query parameters if request parameters are not found in JSON body
            if (! $latitude && $request->has('latitude')) {
                $latitude = (float) $request->query('latitude');
            }
            if (! $longitude && $request->has('longitude')) {
                $longitude = (float) $request->query('longitude');
            }
            if (! $radius && $request->has('radius')) {
                $radius = (float) $request->query('radius');
            }

            Log::info('Fetching nearby drivers', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius,
            ]);

            Log::info('Querying drivers with Haversine formula', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius,
            ]);

            // Use Haversine formula to find drivers within radius
            // 3959 is Earth's radius in miles
            $drivers = \App\Models\Driver::selectRaw("
                    id,
                    name, 
                    vehicle_number as vehicle,
                    current_latitude as latitude,
                    current_longitude as longitude,
                    status,
                    is_active,
                    CASE 
                        WHEN status = 'available' THEN '#4CAF50'
                        WHEN status = 'busy' THEN '#FF9800'
                        ELSE '#9E9E9E'
                    END as status_color,
                    (3959 * acos(cos(radians(?)) * cos(radians(current_latitude)) * 
                    cos(radians(current_longitude) - radians(?)) + 
                    sin(radians(?)) * sin(radians(current_latitude)))) AS distance,
                    last_location_update
                ", [$latitude, $longitude, $latitude])
                ->whereNotNull('current_latitude')
                ->whereNotNull('current_longitude')
                ->where('last_location_update', '>=', now()->subHours(24))
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->get()
                ->map(function ($driver) {
                    return [
                        'id' => $driver->id,
                        'name' => $driver->name,
                        'vehicle' => $driver->vehicle,
                        'latitude' => (float) $driver->latitude,
                        'longitude' => (float) $driver->longitude,
                        'status' => $driver->status,
                        'is_active' => (bool) $driver->is_active,
                        'status_color' => $driver->status_color,
                        'distance' => round($driver->distance, 1),
                        'last_update' => $driver->last_location_update ?
                                        $driver->last_location_update->diffForHumans() : 'Unknown',
                    ];
                });

            // Get total active drivers count for reference
            $totalCount = \App\Models\Driver::where('last_location_update', '>=', now()->subHours(24))
                ->whereNotNull('current_latitude')
                ->whereNotNull('current_longitude')
                ->count();

            return response()->json([
                'success' => true,
                'drivers' => $drivers,
                'total_count' => $totalCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching nearby drivers: '.$e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching nearby drivers: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(Ad $campaign)
    {
        $user = Auth::user();

        // Check if user owns this campaign
        if ($campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Get packages for the edit form
        $packages = Package::active()->orderBy('priority_level')->get();

        return view('campaign-edit', compact('campaign', 'packages'));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, Ad $campaign)
    {
        $user = Auth::user();

        // Check if user owns this campaign
        if ($campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $hasPackages = Package::active()->count() > 0;

        $rules = [
            'campaign_name' => 'required|string|max:255',
            'ctaUrl' => 'nullable|url',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,mpeg|max:51200',
            'selected_latitude' => 'nullable|numeric',
            'selected_longitude' => 'nullable|numeric',
            'selected_radius' => 'nullable|numeric|min:1|max:50',
            'daily_budget' => 'required|numeric|min:1|max:1000',
            'scheduled_date' => 'nullable|date',
            'save_as_draft' => 'nullable|boolean',
        ];

        if ($hasPackages) {
            $rules['selected_package_id'] = 'required|exists:packages,id';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $updateData = [
                'campaign_name' => $request->campaign_name,
                'cta_url' => $request->ctaUrl,
                'latitude' => $request->selected_latitude,
                'longitude' => $request->selected_longitude,
                'radius_miles' => $request->selected_radius ? round($request->selected_radius * 0.621371, 2) : null,
                'budget' => $request->daily_budget ?? 1.00,
                'scheduled_date' => $request->scheduled_date ?: now()->format('Y-m-d'),
            ];

            // Handle file upload if provided
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $mediaPath = $file->store('campaign_media', 'public');
                $mimeType = $file->getMimeType();
                $mediaType = str_starts_with($mimeType, 'video/') ? Ad::MEDIA_TYPE_VIDEO : Ad::MEDIA_TYPE_IMAGE;

                $updateData['media_path'] = $mediaPath;
                $updateData['media_type'] = $mediaType;
            }

            // Handle package selection
            if ($hasPackages && $request->selected_package_id) {
                $updateData['package_id'] = $request->selected_package_id;
            }

            // Handle draft status
            $isDraft = $request->boolean('save_as_draft');
            $updateData['status'] = $isDraft ? Ad::STATUS_DRAFT : Ad::STATUS_ACTIVE;

            $campaign->update($updateData);

            DB::commit();

            $message = $isDraft
                ? 'Campaign updated and saved as draft!'
                : 'Campaign updated successfully!';

            return redirect()->route('camplain-list')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Campaign update failed:', [
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'error' => 'Failed to update campaign: '.$e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Ad $campaign)
    {
        $user = Auth::user();

        // Check if user owns this campaign
        if ($campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        try {
            DB::beginTransaction();

            // If campaign has spent budget, we might want to refund
            if ($campaign->spent < $campaign->budget && $campaign->status !== Ad::STATUS_DRAFT) {
                $refundAmount = $campaign->budget - $campaign->spent;
                if ($refundAmount > 0) {
                    $user->addBalance($refundAmount, 'refund', "Refund for deleted campaign: {$campaign->campaign_name}");
                }
            }

            $campaign->delete();

            DB::commit();

            return redirect()->route('camplain-list')->with('success', 'Campaign deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Campaign deletion failed:', [
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'error' => 'Failed to delete campaign: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Show a specific campaign.
     */
    public function show(Ad $campaign)
    {
        $user = Auth::user();

        // Check if user owns this campaign
        if ($campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('campaign-show', compact('campaign'));
    }

    /**
     * Pause a campaign.
     */
    public function pause(Ad $campaign)
    {
        $user = Auth::user();

        // Check if user owns this campaign
        if ($campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $campaign->update(['status' => Ad::STATUS_PAUSED]);

        return redirect()->back()->with('success', 'Campaign paused successfully!');
    }

    /**
     * Resume a campaign.
     */
    public function resume(Ad $campaign)
    {
        $user = Auth::user();

        // Check if user owns this campaign
        if ($campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $campaign->update(['status' => Ad::STATUS_ACTIVE]);

        return redirect()->back()->with('success', 'Campaign resumed successfully!');
    }

    /**
     * Save campaign as draft via AJAX.
     */
    public function saveDraft(Request $request)
    {
        $user = Auth::user();

        try {
            // Validate basic required fields for draft
            $request->validate([
                'campaign_name' => 'required|string|max:255',
                'media_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,mpeg|max:51200',
            ]);

            DB::beginTransaction();

            // Handle file upload if present
            $mediaPath = null;
            $mediaType = null;
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $mediaPath = $file->store('campaign_media', 'public');
                $mimeType = $file->getMimeType();
                $mediaType = str_starts_with($mimeType, 'video/') ? Ad::MEDIA_TYPE_VIDEO : Ad::MEDIA_TYPE_IMAGE;
            }

            // Create draft campaign
            $ad = Ad::create([
                'user_id' => $user->id,
                'package_id' => $request->selected_package_id ?: null,
                'campaign_name' => $request->campaign_name,
                'media_type' => $mediaType,
                'media_path' => $mediaPath,
                'cta_url' => $request->ctaUrl,
                'latitude' => $request->selected_latitude,
                'longitude' => $request->selected_longitude,
                'radius_miles' => $request->selected_radius ? round($request->selected_radius * 0.621371, 2) : null,
                'status' => Ad::STATUS_DRAFT,
                'budget' => $request->daily_budget ?: 0,
                'spent' => 0,
                'impressions' => 0,
                'qr_scans' => 0,
                'scheduled_date' => $request->scheduled_date ?: now()->format('Y-m-d'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Campaign saved as draft successfully!',
                'draft_id' => $ad->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Draft save failed:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft: ' . $e->getMessage(),
            ], 500);
        }
    }
}
