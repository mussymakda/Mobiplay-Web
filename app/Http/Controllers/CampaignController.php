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
            
        return view('camplain-list', compact('campaigns'));
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
        
        return redirect()->route('camplain-list')->with('success', 'Campaign created successfully! Media saved to: ' . $mediaPath);
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
            'has_file' => $request->hasFile('media_file')
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
            if (!$isDraft && $budget > 0) {
                // Check if user has sufficient balance
                if ($user->total_balance < $budget) {
                    DB::commit();
                    
                    // Redirect to payment with ad info
                    return redirect()->route('payment.make', [
                        'ad_id' => $ad->id,
                        'amount' => $budget,
                        'description' => "Payment for campaign: {$ad->campaign_name}"
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
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'Failed to create campaign: ' . $e->getMessage()
            ])->withInput();
        }
    }
}