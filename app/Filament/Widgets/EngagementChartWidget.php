<?php

namespace App\Filament\Widgets;

use App\Models\Impression;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class EngagementChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Ad Engagement';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                        'boxWidth' => 8,
                        'boxHeight' => 8,
                        'color' => '#6b7280',
                    ],
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(200, 200, 200, 0.1)',
                    ],
                    'ticks' => [
                        'color' => '#9ca3af',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'color' => 'rgba(200, 200, 200, 0.1)',
                    ],
                    'ticks' => [
                        'color' => '#9ca3af',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'beginAtZero' => true,
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'ticks' => [
                        'color' => '#9ca3af',
                        'callback' => '(value) => value + "%"',
                    ],
                    'min' => 0,
                    'max' => 20,
                    'title' => [
                        'display' => true,
                        'text' => 'QR Scan Rate (%)',
                        'color' => '#9ca3af',
                    ],
                ],
            ],
            'elements' => [
                'bar' => [
                    'borderWidth' => 1,
                ],
                'line' => [
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ],
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ],
            ],
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'layout' => [
                'padding' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $days = 14; // Last 14 days
        $dates = [];
        $impressionsData = [];
        $qrScansData = [];
        $scanRateData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('M d');

            // Get impression counts
            $impressionCount = Impression::whereDate('created_at', $formattedDate)
                ->where('type', Impression::TYPE_DISPLAY)
                ->count();

            // Get QR scan counts
            $qrScanCount = Impression::whereDate('created_at', $formattedDate)
                ->where('type', Impression::TYPE_QR_SCAN)
                ->count();

            // Calculate scan rate percentage
            $scanRate = $impressionCount > 0
                ? round(($qrScanCount / $impressionCount) * 100, 1)
                : 0;

            $dates[] = $displayDate;
            $impressionsData[] = $impressionCount;
            $qrScansData[] = $qrScanCount;
            $scanRateData[] = $scanRate;
        }

        return [
            'datasets' => [
                [
                    'type' => 'bar',
                    'label' => 'Impressions',
                    'data' => $impressionsData,
                    'backgroundColor' => 'rgba(75, 85, 99, 0.7)',
                    'borderColor' => '#4b5563',
                    'borderWidth' => 1,
                    'borderRadius' => 3,
                    'barPercentage' => 0.6,
                ],
                [
                    'type' => 'bar',
                    'label' => 'QR Scans',
                    'data' => $qrScansData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 1,
                    'borderRadius' => 3,
                    'barPercentage' => 0.6,
                ],
                [
                    'type' => 'line',
                    'label' => 'QR Scan Rate',
                    'data' => $scanRateData,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                    'pointBackgroundColor' => '#f59e0b',
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                    'pointStyle' => 'circle',
                    'pointRadius' => 3,
                    'pointHoverRadius' => 5,
                    'tension' => 0.4,
                    'fill' => false,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
