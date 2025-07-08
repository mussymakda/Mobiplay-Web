<?php

namespace App\Filament\Widgets;

use App\Models\Impression;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class QrImpressionsComparisonWidget extends ChartWidget
{
    protected static ?string $heading = 'QR Scans vs Impressions';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 2;
    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
                'foreColor' => '#aaa',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'stroke' => [
                'curve' => 'smooth',
                'width' => [3, 3],
                'dashArray' => [0, 5],
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'left',
            ],
            'grid' => [
                'strokeDashArray' => 4,
                'borderColor' => '#555',
                'row' => [
                    'opacity' => 0.5,
                ],
            ],
            'markers' => [
                'size' => 5,
                'strokeWidth' => 0,
                'hover' => [
                    'size' => 7,
                ],
            ],
            'xaxis' => [
                'categories' => $this->getDates(),
                'labels' => [
                    'style' => [
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'formatter' => 'function (value) { return Math.round(value).toLocaleString(); }',
                ],
            ],
            'tooltip' => [
                'theme' => 'dark',
                'y' => [
                    'formatter' => 'function (value) { return value.toLocaleString(); }',
                ],
            ],
            'colors' => ['#10b981', '#eab308'],
        ];
    }

    protected function getData(): array
    {
        $period = $this->getPeriod();
        $startDate = $period->first();
        $endDate = $period->last();

        // Get impressions
        $impressions = Impression::where('type', Impression::TYPE_DISPLAY)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn ($item) => (int) $item->total);

        // Get QR scans
        $qrScans = Impression::where('type', Impression::TYPE_QR_SCAN)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn ($item) => (int) $item->total);

        // Fill in the missing dates with zeros
        $impressionsData = [];
        $qrScansData = [];

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $impressionsData[] = $impressions[$dateStr] ?? 0;
            $qrScansData[] = $qrScans[$dateStr] ?? 0;
        }

        return [
            'series' => [
                [
                    'name' => 'Impressions',
                    'data' => $impressionsData,
                ],
                [
                    'name' => 'QR Scans',
                    'data' => $qrScansData,
                ],
            ],
        ];
    }

    protected function getDates(): array
    {
        $period = $this->getPeriod();
        $dates = [];
        
        foreach ($period as $date) {
            $dates[] = $date->format('M d');
        }
        
        return $dates;
    }

    protected function getPeriod(): CarbonPeriod
    {
        return CarbonPeriod::create(now()->subDays(14), now());
    }
}
