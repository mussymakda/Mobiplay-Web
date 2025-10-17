<?php

namespace App\Filament\Widgets;

use App\Models\Ad;
use App\Models\Impression;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ImpressionStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Total impressions
        $totalImpressions = Ad::sum('impressions');

        // Today's impressions
        $todayImpressions = Impression::whereDate('created_at', $today)
            ->where('type', Impression::TYPE_DISPLAY)
            ->count();

        // This month's impressions
        $thisMonthImpressions = Impression::where('created_at', '>=', $thisMonth)
            ->where('type', Impression::TYPE_DISPLAY)
            ->count();

        // Last month's impressions for comparison
        $lastMonthImpressions = Impression::whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->where('type', Impression::TYPE_DISPLAY)
            ->count();

        // Impression growth rate
        $impressionGrowth = $lastMonthImpressions > 0
            ? (($thisMonthImpressions - $lastMonthImpressions) / $lastMonthImpressions) * 100
            : 0;

        // Total QR scans
        $totalQrScans = Ad::sum('qr_scans');

        // Today's QR scans
        $todayQrScans = Impression::whereDate('created_at', $today)
            ->where('type', Impression::TYPE_QR_SCAN)
            ->count();

        // This month's QR scans
        $thisMonthQrScans = Impression::where('created_at', '>=', $thisMonth)
            ->where('type', Impression::TYPE_QR_SCAN)
            ->count();

        // QR scan conversion rate (QR scans / impressions)
        $qrScanRate = $totalImpressions > 0
            ? ($totalQrScans / $totalImpressions) * 100
            : 0;

        // Average cost per impression
        $totalCost = Impression::sum('cost');
        $costPerImpression = $totalImpressions > 0
            ? $totalCost / $totalImpressions
            : 0;

        // Recent daily impression trend (for sparkline chart)
        $dailyImpressions = Impression::where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('type', Impression::TYPE_DISPLAY)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        return [
            Stat::make('Total Impressions', number_format($totalImpressions))
                ->description($thisMonthImpressions > $lastMonthImpressions
                    ? '+'.round($impressionGrowth, 1).'% vs last month'
                    : round($impressionGrowth, 1).'% vs last month')
                ->descriptionIcon($thisMonthImpressions > $lastMonthImpressions ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($dailyImpressions)
                ->color($thisMonthImpressions > $lastMonthImpressions ? 'success' : 'danger'),

            Stat::make('Today\'s Impressions', number_format($todayImpressions))
                ->description(number_format($thisMonthImpressions).' this month')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('QR Scan Rate', round($qrScanRate, 2).'%')
                ->description(number_format($totalQrScans).' total scans')
                ->descriptionIcon('heroicon-m-qr-code')
                ->chart(Impression::where('created_at', '>=', Carbon::now()->subDays(7))
                    ->where('type', Impression::TYPE_QR_SCAN)
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('count')
                    ->toArray())
                ->color('success'),
        ];
    }
}
