<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * Display the campaign list for the authenticated user
     */
    public function index()
    {
        // Get published campaigns (non-draft)
        $publishedCampaigns = Ad::where('user_id', Auth::id())
            ->where('status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get draft campaigns
        $draftCampaigns = Ad::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('camplain-list', compact('publishedCampaigns', 'draftCampaigns'));
    }

    /**
     * Show the campaign creation wizard
     */
    public function create()
    {
        $packages = Package::all();

        return view('campaign-wizard', compact('packages'));
    }

    /**
     * Store a new campaign
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'media_file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
            'cta_url' => 'required|url',
            'cta_text' => 'nullable|string|max:255',
            'qr_position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right',
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_miles' => 'required|numeric|min:0.1|max:50',
            'package_id' => 'required|exists:packages,id',
            'budget' => 'required|numeric|min:10|max:10000', // This will become daily_budget
        ]);

        // Validate that the location is within Mexico
        if (! $this->isLocationInMexico($validated['latitude'], $validated['longitude'])) {
            return redirect()->back()
                ->withErrors(['location' => 'Campaign locations are currently only available within Mexico. Please select a location within Mexico.'])
                ->withInput();
        }

        // For backward compatibility, treat 'budget' input as 'daily_budget'
        $validated['daily_budget'] = $validated['budget'];
        $validated['budget'] = 0; // Set total budget to 0 for new daily system

        $user = Auth::user();
        $package = Package::findOrFail($validated['package_id']);

        // Check if user has sufficient balance for at least the daily budget
        if ($user->total_balance < $validated['daily_budget']) {
            return redirect()->back()
                ->withErrors(['budget' => 'Insufficient balance for daily ad spend. Please add funds to your account.'])
                ->withInput();
        }

        // Handle media file upload
        $mediaPath = null;
        $mediaType = 'image'; // Default
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $filename = Str::random(32).'.'.$file->getClientOriginalExtension();
            $mediaPath = $file->storeAs('campaigns', $filename, 'public');

            // Determine media type based on file extension
            $extension = strtolower($file->getClientOriginalExtension());
            $mediaType = in_array($extension, ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image';
        }

        // Create the campaign
        $campaign = Ad::create([
            'user_id' => $user->id,
            'package_id' => $validated['package_id'],
            'campaign_name' => $validated['campaign_name'],
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'cta_url' => $validated['cta_url'],
            'cta_text' => $validated['cta_text'] ?? null,
            'qr_position' => $validated['qr_position'] ?? 'top-right',
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'location_name' => $validated['location_name'],
            'radius_miles' => $validated['radius_miles'],
            'budget' => $validated['budget'], // 0 for new daily system
            'daily_budget' => $validated['daily_budget'],
            'spent' => 0,
            'daily_spent' => 0,
            'last_reset_date' => now('America/Mexico_City')->toDateString(),
            'impressions' => 0,
            'qr_scans' => 0,
            'status' => Ad::STATUS_PENDING,
        ]);

        // Note: With daily budgets, we don't deduct the full amount upfront
        // Instead, budget is deducted as impressions occur via the impression tracking system

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully! It will be reviewed and activated soon.');
    }

    /**
     * Save campaign as draft
     */
    public function saveDraft(Request $request)
    {
        $user = Auth::user();

        // Validate only the fields that are present
        $rules = [
            'campaign_name' => 'nullable|string|max:255',
            'media_type' => 'nullable|string|in:image,video',
            'media_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
            'cta_url' => 'nullable|url',
            'cta_text' => 'nullable|string|max:255',
            'qr_position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right',
            'location_name' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius_miles' => 'nullable|numeric|min:0.1|max:50',
            'package_id' => 'nullable|exists:packages,id',
            'budget' => 'nullable|numeric|min:1', // This becomes daily_budget
        ];

        $validated = $request->validate($rules);

        // Handle file upload if present
        $mediaPath = null;
        $mediaType = null;
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $filename = Str::random(32).'.'.$file->getClientOriginalExtension();
            $mediaPath = $file->storeAs('campaigns', $filename, 'public');

            // Determine media type based on file extension
            $extension = strtolower($file->getClientOriginalExtension());
            $mediaType = in_array($extension, ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image';
        }

        // Ensure media_type is never null
        if (! $mediaType) {
            $mediaType = 'image'; // Default fallback
        }

        // Get default package for drafts (Basic Package)
        $defaultPackageId = $validated['package_id'] ?? 1; // Use Basic Package as default

        // Create draft campaign with all required NOT NULL fields
        $draft = Ad::create([
            'user_id' => $user->id,
            'package_id' => $defaultPackageId,
            'campaign_name' => $validated['campaign_name'] ?? 'Draft Campaign',
            'media_type' => $mediaType, // Always has a value
            'media_path' => $mediaPath,
            'cta_url' => $validated['cta_url'] ?? '#', // NOT NULL constraint requires a value
            'cta_text' => $validated['cta_text'] ?? null,
            'qr_position' => $validated['qr_position'] ?? 'top-right',
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'radius_miles' => $validated['radius_miles'] ?? 5, // Use database default
            'budget' => 0, // Total budget set to 0 for daily system
            'daily_budget' => $validated['budget'] ?? 0, // Use budget input as daily_budget
            'spent' => 0,
            'daily_spent' => 0,
            'last_reset_date' => now('America/Mexico_City')->toDateString(),
            'impressions' => 0,
            'qr_scans' => 0,
            'status' => Ad::STATUS_DRAFT,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Campaign saved as draft successfully!',
            'draft_id' => $draft->id,
        ]);
    }

    /**
     * Show a specific campaign
     */
    public function show(Ad $campaign)
    {
        // Ensure user can only view their own campaigns
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Load the package relationship for display
        $campaign->load('package');

        return view('campaign-detail', compact('campaign'));
    }

    /**
     * Show the edit form for a campaign
     */
    public function edit(Ad $campaign)
    {
        // Ensure user can only edit their own campaigns
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Allow editing of draft, pending, or paused campaigns
        if (! in_array($campaign->status, [Ad::STATUS_DRAFT, Ad::STATUS_PENDING, Ad::STATUS_PAUSED])) {
            return redirect()->route('campaigns.show', $campaign)
                ->with('error', 'This campaign cannot be edited in its current status.');
        }

        $packages = Package::all();

        return view('campaign-edit', compact('campaign', 'packages'));
    }

    /**
     * Update a campaign
     */
    public function update(Request $request, Ad $campaign)
    {
        Log::info('Campaign update called', [
            'campaign_id' => $campaign->id,
            'action' => $request->input('action'),
            'request_data' => $request->all(),
        ]);

        // Ensure user can only update their own campaigns
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Allow updating of draft, pending, or paused campaigns
        if (! in_array($campaign->status, [Ad::STATUS_DRAFT, Ad::STATUS_PENDING, Ad::STATUS_PAUSED])) {
            return redirect()->route('campaigns.show', $campaign)
                ->with('error', 'This campaign cannot be updated in its current status.');
        }

        $action = $request->input('action', 'update');
        $isDraft = $campaign->status === Ad::STATUS_DRAFT;

        // Different validation rules for drafts vs published campaigns
        if ($isDraft && $action === 'save_draft') {
            $validated = $request->validate([
                'campaign_name' => 'nullable|string|max:255',
                'cta_url' => 'nullable|string|max:255', // Allow any string for drafts
                'cta_text' => 'nullable|string|max:255',
                'qr_position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right',
                'location_name' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'radius_miles' => 'nullable|numeric|min:0.1|max:50',
                'daily_budget' => 'nullable|numeric|min:0', // Allow 0 for drafts
                'package_id' => 'nullable|exists:packages,id',
                'media_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
            ]);
        } else {
            // Full validation for publishing or updating published campaigns
            $validated = $request->validate([
                'campaign_name' => 'required|string|max:255',
                'cta_url' => 'required|url',
                'cta_text' => 'nullable|string|max:255',
                'qr_position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right',
                'location_name' => 'required|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius_miles' => 'required|numeric|min:0.1|max:50',
                'daily_budget' => 'required|numeric|min:10|max:10000',
                'package_id' => $isDraft ? 'required|exists:packages,id' : 'nullable',
                'media_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
            ]);
        }

        // Validate Mexico location for non-draft campaigns or when publishing
        if ((! $isDraft || $action === 'publish') &&
            isset($validated['latitude']) && isset($validated['longitude']) &&
            ! $this->isLocationInMexico($validated['latitude'], $validated['longitude'])) {
            return redirect()->back()
                ->withErrors(['location' => 'Campaign locations are currently only available within Mexico. Please select a location within Mexico.'])
                ->withInput();
        }

        $user = Auth::user();

        // Handle daily budget changes for published campaigns
        if (! $isDraft || $action === 'publish') {
            $newDailyBudget = $validated['daily_budget'] ?? 0;

            // For daily budgets, just validate user has sufficient balance for the daily amount
            if ($newDailyBudget > 0 && $user->total_balance < $newDailyBudget) {
                return redirect()->back()
                    ->withErrors(['daily_budget' => 'Insufficient balance for daily ad spend.'])
                    ->withInput();
            }
        }

        // Handle media file upload if provided
        if ($request->hasFile('media_file')) {
            // Delete old media file
            if ($campaign->media_path) {
                Storage::disk('public')->delete($campaign->media_path);
            }

            $file = $request->file('media_file');
            $filename = Str::random(32).'.'.$file->getClientOriginalExtension();
            $validated['media_path'] = $file->storeAs('campaigns', $filename, 'public');
            $validated['media_type'] = in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image';
        }

        // Set status based on action
        if ($action === 'publish' && $isDraft) {
            $validated['status'] = Ad::STATUS_PENDING;
        } elseif ($action === 'save_draft') {
            $validated['status'] = Ad::STATUS_DRAFT;
        }

        // Update the campaign
        $filteredData = array_filter($validated, function ($value) {
            return $value !== null;
        });

        Log::info('Updating campaign', [
            'campaign_id' => $campaign->id,
            'filtered_data' => $filteredData,
            'action' => $action,
        ]);

        $campaign->update($filteredData);

        // Note: With daily budgets, we don't handle upfront deductions
        // Budget is deducted in real-time as impressions occur

        $message = match ($action) {
            'publish' => 'Campaign published successfully! It will be reviewed and activated soon.',
            'save_draft' => 'Draft saved successfully!',
            default => 'Campaign updated successfully!'
        };

        return redirect()->route('camplain-list')
            ->with('success', $message);
    }

    /**
     * Pause a campaign
     */
    public function pause(Ad $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->status !== Ad::STATUS_ACTIVE) {
            return redirect()->back()->with('error', 'Only active campaigns can be paused.');
        }

        $campaign->update(['status' => Ad::STATUS_PAUSED]);

        return redirect()->back()->with('success', 'Campaign paused successfully.');
    }

    /**
     * Resume a paused campaign
     */
    public function resume(Ad $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->status !== Ad::STATUS_PAUSED) {
            return redirect()->back()->with('error', 'Only paused campaigns can be resumed.');
        }

        $campaign->update(['status' => Ad::STATUS_ACTIVE]);

        return redirect()->back()->with('success', 'Campaign resumed successfully.');
    }

    /**
     * Delete a campaign
     */
    public function destroy(Ad $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion of pending campaigns
        if ($campaign->status !== Ad::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Only pending campaigns can be deleted.');
        }

        $user = Auth::user();
        // With daily budgets, there's no upfront deduction to refund
        // Only refund if there was old-style budget deduction
        $refundAmount = $campaign->budget - $campaign->spent;

        // Delete media file
        if ($campaign->media_path) {
            Storage::disk('public')->delete($campaign->media_path);
        }

        // Note: With new daily budget system, no refund needed as no upfront deduction
        $message = 'Campaign deleted successfully.';
        if ($refundAmount > 0) {
            // This handles legacy campaigns with upfront budget deduction
            // $user->addBalance($refundAmount, 'refund', 'Campaign deletion refund: '.$campaign->campaign_name);
            $message = 'Campaign deleted successfully. Legacy budget refund may apply.';
        }

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', $message);
    }

    /**
     * Get nearby drivers for campaign location visualization
     */
    public function getNearbyDrivers(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:100', // radius in miles
        ]);

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        $radius = (float) ($request->radius ?? 25); // Default 25 mile radius

        // Use Haversine formula to find nearby drivers (using miles)
        $drivers = \App\Models\Driver::select([
            'id',
            'name',
            'device_id',
            'vehicle_number',
            'current_latitude',
            'current_longitude',
            'status',
            'last_location_update',
            'daily_distance_km',
            DB::raw("
                (3959 * acos(
                    cos(radians($latitude)) * 
                    cos(radians(current_latitude)) * 
                    cos(radians(current_longitude) - radians($longitude)) + 
                    sin(radians($latitude)) * 
                    sin(radians(current_latitude))
                )) AS distance
            "),
        ])
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->where('is_active', true)
            ->having('distance', '<=', $radius)
            ->where('last_location_update', '>=', now()->subHours(2)) // Only show drivers active in last 2 hours
            ->orderBy('distance')
            ->limit(50) // Limit to 50 drivers for performance
            ->get();

        // Format the response for the map
        $driversData = $drivers->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'device_id' => $driver->device_id,
                'latitude' => (float) $driver->current_latitude,
                'longitude' => (float) $driver->current_longitude,
                'is_active' => $driver->is_active,
                'distance' => round($driver->distance, 2),
                'last_update' => $driver->last_location_update ? $driver->last_location_update->diffForHumans() : 'Unknown',
                'vehicle' => $driver->vehicle_number ?: 'N/A',
                'daily_distance' => $driver->daily_distance_km,
                'status_color' => $driver->is_active ? '#10B981' : '#6B7280', // green for active, gray for inactive
            ];
        });

        return response()->json([
            'success' => true,
            'drivers' => $driversData,
            'total_count' => $driversData->count(),
            'search_radius_miles' => $radius,
            'center' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
        ]);
    }

    /**
     * Check if coordinates are within Mexico's borders
     */
    private function isLocationInMexico($latitude, $longitude)
    {
        // Mexico's approximate bounding box
        $mexicoBounds = [
            'north' => 32.72,     // Northern border with US
            'south' => 14.32,     // Southern border with Guatemala/Belize
            'east' => -86.70,     // Eastern coast (Caribbean)
            'west' => -118.45,    // Western coast (Pacific)
        ];

        return $latitude >= $mexicoBounds['south'] &&
               $latitude <= $mexicoBounds['north'] &&
               $longitude >= $mexicoBounds['west'] &&
               $longitude <= $mexicoBounds['east'];
    }
}
