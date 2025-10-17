<?php

namespace App\Filament\Widgets;

use App\Models\Ad;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KeyMetricsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Total users
        $totalUsers = User::count();

        // Active campaigns
        $activeCampaigns = Ad::where('status', Ad::STATUS_ACTIVE)->count();
        $totalCampaigns = Ad::count();

        // This month's revenue
        $thisMonthRevenue = Payment::where('type', Payment::TYPE_DEPOSIT)
            ->where('status', Payment::STATUS_COMPLETED)
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount');

        // Last month's revenue
        $lastMonthRevenue = Payment::where('type', Payment::TYPE_DEPOSIT)
            ->where('status', Payment::STATUS_COMPLETED)
            ->whereBetween('created_at', [$lastMonth, $thisMonth->copy()->subDay()])
            ->sum('amount');

        // Calculate month-over-month growth
        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Get total campaign budget
        $totalBudget = Ad::sum('budget');

        // Total ad spend to date
        $totalSpent = Payment::where('type', Payment::TYPE_AD_SPEND)
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');

        $totalSpent = abs($totalSpent); // Ensure it's positive for display

        // Budget utilization
        $budgetUtilization = $totalBudget > 0
            ? round(($totalSpent / $totalBudget) * 100, 1)
            : 0;

        return [
            Stat::make('Active Campaigns', $activeCampaigns)
                ->description($activeCampaigns.' of '.$totalCampaigns.' campaigns running')
                ->descriptionIcon('heroicon-m-play')
                ->chart([3, 5, 2, 3, 7, $activeCampaigns])
                ->color('success'),

            Stat::make('Revenue This Month', '$'.number_format($thisMonthRevenue, 2))
                ->description($revenueGrowth >= 0
                    ? '+'.$revenueGrowth.'% vs last month'
                    : $revenueGrowth.'% vs last month')
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger')
                ->chart([
                    $lastMonthRevenue * 0.3,
                    $lastMonthRevenue * 0.5,
                    $lastMonthRevenue * 0.7,
                    $lastMonthRevenue * 0.9,
                    $lastMonthRevenue,
                    $thisMonthRevenue,
                ]),

            Stat::make('Budget Utilization', $budgetUtilization.'%')
                ->description('$'.number_format($totalSpent, 2).' of $'.number_format($totalBudget, 2))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($budgetUtilization < 50 ? 'info' : ($budgetUtilization < 90 ? 'warning' : 'success'))
                ->chart([$budgetUtilization]),
        ];
    }
}
