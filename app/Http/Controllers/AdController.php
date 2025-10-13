<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    public function getAds(): JsonResponse
    {
        // Fetch all active ads from the database
        $ads = Ad::where('status', Ad::STATUS_ACTIVE)->get()->map(function ($ad) {
            // Return the ad data mapped to correct model fields
            return [
                'id' => $ad->id,
                'campaign_name' => $ad->campaign_name,
                'media_type' => $ad->media_type,
                'media_path' => $ad->media_path,
                'cta_url' => $ad->cta_url,
                'qr_code_url' => $ad->qr_code_url,
                'latitude' => $ad->latitude,
                'longitude' => $ad->longitude,
                'location_name' => $ad->location_name,
                'radius_miles' => $ad->radius_miles,
                'status' => $ad->status,
                'budget' => $ad->budget,
                'user_id' => $ad->user_id,
                'created_at' => $ad->created_at,
                'updated_at' => $ad->updated_at,
            ];
        });

        // Return the ads data as a JSON response
        return response()->json($ads);
    }
}
