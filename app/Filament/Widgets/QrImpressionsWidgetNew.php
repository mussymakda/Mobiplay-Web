<?php

namespace App\Filament\Widgets;

use App\Models\Impression;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class QrImpressionsWidgetNew extends ChartWidget
{
    protected static ?string $heading = 'QR Scans & Impressions Over Time';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '400px';
    protected int|string|array $columnSpan = 1;
    
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
        $impressions = [];
        $qrScans = [];

        // Get the last 14 days
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('M d');

            // Get total impressions for this day
            $dailyImpressions = Impression::whereDate('created_at', $date->toDateString())->count();
            $impressions[] = $dailyImpressions;

            // Get QR scans for this day
            $dailyQrScans = Impression::whereDate('created_at', $date->toDateString())
                ->where('type', Impression::TYPE_QR_SCAN)
                ->count();
            $qrScans[] = $dailyQrScans;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Impressions',
                    'data' => $impressions,
                    'borderColor' => 'rgb(0, 0, 0)',
                    'backgroundColor' => 'rgba(0, 0, 0, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgb(0, 0, 0)',
                    'pointBorderColor' => 'rgb(255, 255, 255)',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
                [
                    'label' => 'QR Scans',
                    'data' => $qrScans,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => 'rgb(255, 255, 255)',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $dates,
        ];
    }
}
