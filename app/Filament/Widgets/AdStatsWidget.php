<?php

namespace App\Filament\Widgets;

use App\Models\Ad;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Active campaigns
        $activeCampaigns = Ad::where('status', Ad::STATUS_ACTIVE)->count();

        // Total campaigns
        $totalCampaigns = Ad::count();

        // This month's new campaigns
        $newCampaigns = Ad::where('created_at', '>=', $thisMonth)->count();

        // Last month's new campaigns for comparison
        $lastMonthCampaigns = Ad::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();

        // Campaign growth rate
        $campaignGrowth = $lastMonthCampaigns > 0
            ? (($newCampaigns - $lastMonthCampaigns) / $lastMonthCampaigns) * 100
            : 0;

        // Total ad budget across all campaigns
        $totalBudget = Ad::sum('budget');

        // Total spent across all campaigns
        $totalSpent = Ad::sum('spent');

        // Budget utilization percentage
        $budgetUtilization = $totalBudget > 0
            ? ($totalSpent / $totalBudget) * 100
            : 0;

        // Today's newly created campaigns
        $todayNewCampaigns = Ad::whereDate('created_at', $today)->count();

        return [
            Stat::make('Active Campaigns', $activeCampaigns)
                ->description($activeCampaigns.' of '.$totalCampaigns.' campaigns running')
                ->descriptionIcon('heroicon-m-play')
                ->chart(Ad::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('count')
                    ->toArray())
                ->color('success'),

            Stat::make('New Campaigns', $newCampaigns.' this month')
                ->description($campaignGrowth >= 0
                    ? '+'.round($campaignGrowth, 1).'% vs last month'
                    : round($campaignGrowth, 1).'% vs last month')
                ->descriptionIcon($campaignGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($campaignGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('Budget Utilization', round($budgetUtilization, 1).'%')
                ->description('$'.number_format($totalSpent, 2).' of $'.number_format($totalBudget, 2))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([
                    $totalSpent, $totalBudget - $totalSpent,
                ])
                ->color($budgetUtilization < 85 ? 'info' : ($budgetUtilization < 95 ? 'warning' : 'danger')),
        ];
    }
}
