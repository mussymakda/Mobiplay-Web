<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DriverStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected static ?int $sort = 1;

    protected $listeners = ['refreshWidgets' => '$refresh'];

    public function refreshWidget()
    {
        // Force refresh of the widget data
        $this->render();
    }

    protected function getStats(): array
    {
        $today = Carbon::today();

        // Driver counts by activity status
        $totalDrivers = Driver::count();
        $activeDrivers = Driver::where('is_active', true)->count();
        $inactiveDrivers = Driver::where('is_active', false)->count();

        // Today's new driver registrations
        $newDriversToday = Driver::whereDate('created_at', $today)->count();

        // Recently active drivers (last 2 hours)
        $recentlyActive = Driver::where('last_location_update', '>=', Carbon::now()->subHours(2))->count();

        // Calculate activity rate
        $activityRate = $totalDrivers > 0 ? ($activeDrivers / $totalDrivers) * 100 : 0;

        // Driver registration trend for chart (last 7 days)
        $driverTrend = Driver::where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        // Ensure we have 7 data points for the chart (fill with zeros for missing dates)
        while (count($driverTrend) < 7) {
            array_unshift($driverTrend, 0);
        }

        return [
            Stat::make('Total Drivers', number_format($totalDrivers))
                ->description($newDriversToday.' registered today')
                ->descriptionIcon('heroicon-m-users')
                ->chart($driverTrend)
                ->color('primary'),

            Stat::make('Active Drivers', number_format($activeDrivers))
                ->description(round($activityRate, 1).'% active rate')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([$activeDrivers, $inactiveDrivers]),

            Stat::make('Recently Active', number_format($recentlyActive))
                ->description('Updated in last 2 hours')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Recently Active', number_format($recentlyActive))
                ->description('Last 2 hours | '.$inactiveDrivers.' inactive')
                ->descriptionIcon('heroicon-m-signal')
                ->color('info'),
        ];
    }
}
