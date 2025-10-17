<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueExpenseChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue & Budget';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 2;

    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'area';
    }

    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'area',
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
                'width' => 2,
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.3,
                    'opacityTo' => 0.2,
                    'stops' => [0, 90, 100],
                ],
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
                    'formatter' => 'function (value) { return "$" + value.toFixed(0); }',
                ],
            ],
            'tooltip' => [
                'theme' => 'dark',
                'x' => [
                    'format' => 'dd MMM',
                ],
            ],
            'colors' => ['#60a5fa', '#ef4444'],
        ];
    }

    protected function getData(): array
    {
        $period = $this->getPeriod();
        $startDate = $period->first();
        $endDate = $period->last();

        // Get deposits (money added)
        $deposits = Payment::where('type', Payment::TYPE_DEPOSIT)
            ->where('status', Payment::STATUS_COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn ($item) => round($item->total, 2));

        // Get ad spend
        $adSpend = Payment::where('type', Payment::TYPE_AD_SPEND)
            ->where('status', Payment::STATUS_COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(ABS(amount)) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn ($item) => round($item->total, 2));

        // Fill in the missing dates with zeros
        $depositsData = [];
        $adSpendData = [];

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $depositsData[] = $deposits[$dateStr] ?? 0;
            $adSpendData[] = $adSpend[$dateStr] ?? 0;
        }

        return [
            'series' => [
                [
                    'name' => 'Money Added',
                    'data' => $depositsData,
                ],
                [
                    'name' => 'Budget Spent',
                    'data' => $adSpendData,
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last 7 days',
            'month' => 'Last 30 days',
            'quarter' => 'Last Quarter',
            'year' => 'This Year',
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
        $filter = $this->filter ?? 'month';

        return match ($filter) {
            'week' => CarbonPeriod::create(now()->subDays(6), now()),
            'month' => CarbonPeriod::create(now()->subDays(29), now()),
            'quarter' => CarbonPeriod::create(now()->subMonths(3), '1 week', now()),
            'year' => CarbonPeriod::create(now()->startOfYear(), '1 month', now()),
        };
    }
}
