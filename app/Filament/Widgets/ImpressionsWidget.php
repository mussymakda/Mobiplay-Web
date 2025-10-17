<?php

namespace App\Filament\Widgets;

use App\Models\Impression;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class ImpressionsWidget extends ChartWidget
{
    protected static ?string $heading = 'Monthly Impressions';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '400px';

    // Correct type declaration for Filament 3
    protected int|string|array $columnSpan = 1;

    // Chart filters
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Weekly',
            'month' => 'Monthly',
            'year' => 'Yearly',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Chart configuration for Apex Charts
    protected function getOptions(): array
    {
        $data = $this->getData();

        return [
            'chart' => [
                'type' => 'bar',
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
            'colors' => ['#000000'],
            'series' => [
                [
                    'name' => 'Impressions',
                    'data' => $data['values'],
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'columnWidth' => '60%',
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
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
                    'text' => 'Impressions',
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
            'dataLabels' => [
                'enabled' => false,
            ],
            'grid' => [
                'borderColor' => '#e0e0e0',
                'strokeDashArray' => 3,
                'position' => 'back',
            ],
            'tooltip' => [
                'theme' => 'light',
                'marker' => [
                    'show' => true,
                ],
                'x' => [
                    'show' => true,
                ],
                'y' => [
                    'formatter' => 'function(value) { return value.toLocaleString() + " impressions" }',
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $period = $this->filter ?? 'month';
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');

                    $count = Impression::whereDate('created_at', $date->format('Y-m-d'))
                        ->where('type', Impression::TYPE_DISPLAY)
                        ->count();
                    $values[] = $count;
                }
                break;

            case 'month':
                // Last 30 days grouped by day
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);

                    // Only show every 3rd label to avoid crowding
                    if ($i % 3 === 0) {
                        $labels[] = $date->format('M d');
                    } else {
                        $labels[] = '';  // Empty label but keeps the data point
                    }

                    $count = Impression::whereDate('created_at', $date->format('Y-m-d'))
                        ->where('type', Impression::TYPE_DISPLAY)
                        ->count();
                    $values[] = $count;
                }
                break;

            case 'year':
                // Last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');

                    $startOfMonth = Carbon::now()->subMonths($i)->startOfMonth();
                    $endOfMonth = Carbon::now()->subMonths($i)->endOfMonth();

                    $count = Impression::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->where('type', Impression::TYPE_DISPLAY)
                        ->count();
                    $values[] = $count;
                }
                break;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    public function getHeading(): string|Htmlable|null
    {
        $period = $this->filter ?? 'month';

        return match ($period) {
            'week' => 'Weekly Impressions',
            'month' => 'Monthly Impressions',
            'year' => 'Yearly Impressions',
            default => 'Monthly Impressions',
        };
    }
}
