<?php

namespace App\Filament\Widgets;

use App\Models\Ad;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KeyStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today = Carbon::today();

        // Total revenue (all time)
        $totalRevenue = Payment::where('type', Payment::TYPE_DEPOSIT)
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');

        // Today's deposits
        $todayDeposits = Payment::where('type', Payment::TYPE_DEPOSIT)
            ->where('status', Payment::STATUS_COMPLETED)
            ->whereDate('created_at', $today)
            ->sum('amount');

        // User stats
        $totalUsers = User::count();
        $activeUsers = User::whereHas('ads', function ($query) {
            $query->where('status', Ad::STATUS_ACTIVE);
        })->count();
        $inactiveUsers = $totalUsers - $activeUsers;
        $newUsers = User::whereDate('created_at', $today)->count();

        return [
            Stat::make('Total Revenue', '$'.number_format($totalRevenue, 0))
                ->description('All time')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Today\'s Deposits', '$'.number_format($todayDeposits, 0))
                ->description('Today only')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([15, 4, 10, 2, 12, 4, 12]),

            Stat::make('User Counts', number_format($totalUsers).' total')
                ->description(number_format($activeUsers).' active, '.number_format($inactiveUsers).' inactive, '.number_format($newUsers).' new today')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('gray')
                ->chart([2, 10, 6, 15, 4, 12, 4]),
        ];
    }
}
