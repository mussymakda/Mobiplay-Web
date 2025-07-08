<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Ad;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PerformanceTrendsWidget extends ChartWidget
{
    protected static ?string $heading = 'Performance Trends';
    protected static ?int $sort = 1;
    protected static ?string $maxHeight = '400px';

    // Correct type declaration for Filament 3
    protected int|string|array $columnSpan = 2;
    
    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                        'font' => [
                            'size' => 12,
                            'weight' => '500',
                        ],
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                        'lineWidth' => 1,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'color' => 'rgba(0, 0, 0, 0.7)',
                        'callback' => 'function(value) { return "$" + value; }',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'color' => 'rgba(0, 0, 0, 0.7)',
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'hoverRadius' => 8,
                ],
                'line' => [
                    'borderWidth' => 3,
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }

    protected function getData(): array
    {
        $dates = [];
        $spent = [];
        $revenue = [];

        // Get the last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('M d');

            // Get money spent on ads for this day
            $dailySpent = Payment::whereDate('created_at', $date->format('Y-m-d'))
                ->where('type', Payment::TYPE_AD_SPEND)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');
            $spent[] = abs(round($dailySpent, 2));

            // Get deposit revenue for this day
            $dailyRevenue = Payment::whereDate('created_at', $date->format('Y-m-d'))
                ->where('type', Payment::TYPE_DEPOSIT)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');
            $revenue[] = round($dailyRevenue, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Budget Spent',
                    'data' => $spent,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgb(239, 68, 68)',
                    'pointBorderColor' => 'rgb(255, 255, 255)',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
                [
                    'label' => 'Deposits',
                    'data' => $revenue,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgb(34, 197, 94)',
                    'pointBorderColor' => 'rgb(255, 255, 255)',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $dates,
        ];
    }
}
