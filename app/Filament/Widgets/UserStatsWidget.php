<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        // Total users
        $totalUsers = User::count();
        
        // Today's new users
        $todayNewUsers = User::whereDate('created_at', $today)->count();
        
        // This month's new users
        $thisMonthNewUsers = User::where('created_at', '>=', $thisMonth)->count();
        
        // Last month's new users for comparison
        $lastMonthNewUsers = User::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        
        // User growth rate
        $userGrowth = $lastMonthNewUsers > 0 
            ? (($thisMonthNewUsers - $lastMonthNewUsers) / $lastMonthNewUsers) * 100 
            : 0;
        
        // Count by user type
        $advertiserCount = User::where('type', 'Advertiser')->count();
        $agencyCount = User::where('type', 'Agency')->count();
        
        // Active users (users with ads)
        $activeUsers = User::whereHas('ads', function ($query) {
            $query->where('status', 'active');
        })->count();
        
        $activeUserPercent = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;
        
        // Verified users percentage
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $verifiedPercent = $totalUsers > 0 ? ($verifiedUsers / $totalUsers) * 100 : 0;
        
        // User registration trend for chart
        $userTrend = User::where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
        
        // Ensure we have 7 data points for the chart (fill with zeros for missing dates)
        while (count($userTrend) < 7) {
            array_unshift($userTrend, 0);
        }
        
        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description($userGrowth >= 0 
                    ? '+' . round($userGrowth, 1) . '% vs last month' 
                    : round($userGrowth, 1) . '% vs last month')
                ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($userTrend)
                ->color($userGrowth >= 0 ? 'success' : 'danger'),
                
            Stat::make('New Users Today', number_format($todayNewUsers))
                ->description(number_format($thisMonthNewUsers) . ' this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
                
            Stat::make('Active Users', $activeUserPercent > 0 
                ? number_format($activeUsers) . ' (' . round($activeUserPercent, 1) . '%)' 
                : 0)
                ->description('Advertisers: ' . $advertiserCount . ' | Agencies: ' . $agencyCount)
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
                
            Stat::make('Email Verification', round($verifiedPercent, 1) . '%')
                ->description($verifiedUsers . ' of ' . $totalUsers . ' users verified')
                ->descriptionIcon('heroicon-m-envelope')
                ->chart([
                    $verifiedUsers, $totalUsers - $verifiedUsers
                ])
                ->color($verifiedPercent > 80 ? 'success' : ($verifiedPercent > 50 ? 'warning' : 'danger')),
        ];
    }
}
