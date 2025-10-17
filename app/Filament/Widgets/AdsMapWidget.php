<?php

namespace App\Filament\Widgets;

use App\Models\Ad;
use Filament\Widgets\Widget;

class AdsMapWidget extends Widget
{
    protected static ?string $heading = 'Active Campaigns Map';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.ads-map-widget';

    public function getViewData(): array
    {
        $activeAds = Ad::where('status', Ad::STATUS_ACTIVE)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['user', 'package'])
            ->get()
            ->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'campaign_name' => $ad->campaign_name,
                    'user_name' => $ad->user->name,
                    'package_name' => $ad->package->name,
                    'latitude' => (float) $ad->latitude,
                    'longitude' => (float) $ad->longitude,
                    'radius_km' => (float) $ad->radius_km,
                    'budget' => $ad->budget,
                    'spent' => $ad->spent,
                    'impressions' => $ad->impressions,
                    'location_name' => $ad->location_name,
                ];
            });

        return [
            'activeAds' => $activeAds,
        ];
    }
}
