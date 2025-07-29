<?php

namespace App\Filament\Widgets;

use App\Models\Impression;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class QrImpressionsWidget extends ChartWidget
{
    protected static ?string $heading = 'QR Scans & Impressions Over Time';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '400px';
    protected int|string|array $columnSpan = 1;
    
    protected function getType(): string
    {
        return 'line';
    }

    // Chart configuration for Apex Charts
    protected function getOptions(): array
    {
        $data = $this->getData();
        
        return [
            'chart' => [
                'type' => 'line',
                'height' => 350,
                'toolbar' => [
                    'show' => true,
                    'tools' => [
                        'download' => true,
                    ],
                ],
                'animations' => [
                    'enabled' => true,
                    'easing' => 'easeinout',
                    'speed' => 800,
                ],
                'fontFamily' => 'inherit',
            ],
            'colors' => ['#000000', '#505050'],
            'series' => [
                [
                    'name' => 'Impressions',
                    'data' => $data['impressions'],
                ],
                [
                    'name' => 'QR Scans',
                    'data' => $data['qr_scans'],
                ],
            ],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 3,
            ],
            'markers' => [
                'size' => 4,
                'strokeColors' => '#ffffff',
                'strokeWidth' => 2,
            ],
            'xaxis' => [
                'categories' => $data['dates'],
                'labels' => [
                    'style' => [
                        'colors' => '#777777',
                        'fontWeight' => 500,
                    ],
                ],
                'axisBorder' => [
                    'show' => false,
                ],
                'axisTicks' => [
                    'show' => false,
                ],
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Count',
                    'style' => [
                        'fontSize' => '12px',
                        'fontWeight' => 500,
                        'color' => '#777777',
                    ],
                ],
                'labels' => [
                    'style' => [
                        'colors' => '#777777',
                        'fontWeight' => 500,
                    ],
                    'formatter' => 'function (value) { return Math.round(value).toLocaleString(); }',
                ],
            ],
            'grid' => [
                'borderColor' => '#e0e0e0',
                'strokeDashArray' => 3,
                'position' => 'back',
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'right',
                'offsetY' => -30,
                'fontSize' => '13px',
                'markers' => [
                    'width' => 12,
                    'height' => 12,
                    'radius' => 6,
                ],
                'itemMargin' => [
                    'horizontal' => 10,
                ],
            ],
            'tooltip' => [
                'theme' => 'light',
                'marker' => [
                    'show' => true,
                ],
                'x' => [
                    'show' => true,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $dates = [];
        $impressions = [];
        $qrScans = [];

        // Last 14 days
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Show date labels for better readability
            $dates[] = $date->format('M d');
            
            // Get impressions for this day
            $dailyImpressions = Impression::whereDate('created_at', $date->format('Y-m-d'))
                ->where('type', Impression::TYPE_DISPLAY)
                ->count();
            $impressions[] = $dailyImpressions;
            
            // Get QR scans for this day
            $dailyQrScans = Impression::whereDate('created_at', $date->format('Y-m-d'))
                ->where('type', Impression::TYPE_QR_SCAN)
                ->count();
            $qrScans[] = $dailyQrScans;
        }

        return [
            'dates' => $dates,
            'impressions' => $impressions,
            'qr_scans' => $qrScans,
        ];
    }
}
