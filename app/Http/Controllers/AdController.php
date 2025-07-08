<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Advert;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    public function getAds(): JsonResponse
    {
        // Fetch all ads from the database
        $ads = Advert::all()->map(function ($ad) {
            // Return the ad data without generating QR codes
            return [
                'id' => $ad->id,
                'name' => $ad->name,
                'type' => $ad->type,
                'media' => $ad->media,
                'cta' => $ad->cta, // Include the CTA link
                'url' => $ad->url,
                'latitude' => $ad->latitude,
                'longitude' => $ad->longitude,
                'radius' => $ad->radius,
                'priority' => $ad->priority,
                'adspend' => $ad->adspend,
                'user_id' => $ad->user_id,
                'created_at' => $ad->created_at,
                'updated_at' => $ad->updated_at,
            ];
        });

        // Return the ads data as a JSON response
        return response()->json($ads);
    }
}
