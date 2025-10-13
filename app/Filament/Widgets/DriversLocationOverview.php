<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\DriverLocationLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DriversLocationOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Get drivers with recent location updates (last 2 hours)
        $activeDrivers = Driver::where('last_location_update', '>=', now()->subHours(2))->count();

        // Get total drivers with location data
        $driversWithLocation = Driver::whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->count();

        // Get today's location updates
        $todayUpdates = DriverLocationLog::whereDate('created_at', today())->count();

        // Get active drivers
        $totalActiveDrivers = Driver::where('is_active', true)->count();

        return [
            Stat::make('Active Drivers', $activeDrivers)
                ->description('Updated location in last 2 hours')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Active Drivers', $totalActiveDrivers)
                ->description('Currently active tablets')
                ->descriptionIcon('heroicon-m-signal')
                ->color('info'),

            Stat::make('Drivers with Location', $driversWithLocation)
                ->description('Have GPS coordinates recorded')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('warning'),

            Stat::make("Today's Location Updates", $todayUpdates)
                ->description('Location logs recorded today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),
        ];
    }
}
