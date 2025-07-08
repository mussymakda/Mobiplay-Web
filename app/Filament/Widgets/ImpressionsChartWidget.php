<?php

namespace App\Filament\Widgets;

use App\Models\Impression;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ImpressionsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Monthly Impressions';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 2;
    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
                'foreColor' => '#aaa',
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '55%',
                    'borderRadius' => 4,
                    'endingShape' => 'rounded',
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'stroke' => [
                'show' => true,
                'width' => 2,
                'colors' => ['transparent'],
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
            'fill' => [
                'opacity' => 0.8,
            ],
            'tooltip' => [
                'theme' => 'dark',
                'y' => [
                    'formatter' => 'function (value) { return value.toLocaleString(); }',
                ],
            ],
            'colors' => ['#60a5fa'],
        ];
    }

    protected function getData(): array
    {
        $period = $this->getPeriod();
        $startDate = $period->first();
        $endDate = $period->last();
        $format = $this->getDateFormat();

        // Get impressions
        $impressions = Impression::where('type', Impression::TYPE_DISPLAY)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$format}') as date"), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn ($item) => (int) $item->total);

        // Fill in the missing dates with zeros
        $impressionsData = [];
        $dateLabels = $this->getDates();

        foreach ($dateLabels as $index => $label) {
            $dateKey = $this->getDateKey($index);
            $impressionsData[] = $impressions[$dateKey] ?? 0;
        }

        return [
            'series' => [
                [
                    'name' => 'Impressions',
                    'data' => $impressionsData,
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Weekly',
            'month' => 'Monthly',
            'year' => 'Yearly',
        ];
    }

    protected function getDates(): array
    {
        $filter = $this->filter ?? 'month';
        $period = $this->getPeriod();
        $dates = [];
        
        if ($filter === 'year') {
            // Monthly labels for yearly view
            foreach ($period as $date) {
                $dates[] = $date->format('M');
            }
        } elseif ($filter === 'month') {
            // Weekly labels for monthly view
            $currentDate = $period->first()->copy();
            while ($currentDate <= $period->last()) {
                $endOfWeek = min($currentDate->copy()->addDays(6), $period->last());
                $dates[] = $currentDate->format('M d') . ' - ' . $endOfWeek->format('M d');
                $currentDate->addDays(7);
            }
        } else {
            // Daily labels for weekly view
            foreach ($period as $date) {
                $dates[] = $date->format('D, M d');
            }
        }
        
        return $dates;
    }

    protected function getDateKey($index): string
    {
        $filter = $this->filter ?? 'month';
        
        if ($filter === 'year') {
            // Return month number for yearly view
            return Carbon::now()->startOfYear()->addMonths($index)->format('m');
        } elseif ($filter === 'month') {
            // Return week number for monthly view
            return (string) ($index + 1);
        } else {
            // Return date for weekly view
            return Carbon::now()->subDays(6 - $index)->format('Y-m-d');
        }
    }

    protected function getDateFormat(): string
    {
        $filter = $this->filter ?? 'month';
        
        return match ($filter) {
            'week' => '%Y-%m-%d',
            'month' => 'WEEK(%Y-%m-%d)',
            'year' => '%m',
        };
    }

    protected function getPeriod(): CarbonPeriod
    {
        $filter = $this->filter ?? 'month';
        
        return match ($filter) {
            'week' => CarbonPeriod::create(now()->subDays(6), now()),
            'month' => CarbonPeriod::create(now()->subDays(29), '7 days', now()),
            'year' => CarbonPeriod::create(now()->startOfYear(), '1 month', now()),
        };
    }
}
