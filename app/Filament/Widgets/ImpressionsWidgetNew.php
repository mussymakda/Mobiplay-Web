<?php

namespace App\Filament\Widgets;

use App\Models\Impression;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class ImpressionsWidgetNew extends ChartWidget
{
    protected static ?string $heading = 'Monthly Impressions';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '400px';

    protected int|string|array $columnSpan = 1;

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

    protected function getData(): array
    {
        $period = $this->filter ?? 'month';
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    $impressions = Impression::whereDate('created_at', $date->toDateString())->count();
                    $values[] = $impressions;
                }
                break;
            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M Y');
                    $impressions = Impression::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $values[] = $impressions;
                }
                break;
            default: // month
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('j');
                    $impressions = Impression::whereDate('created_at', $date->toDateString())->count();
                    $values[] = $impressions;
                }
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Impressions',
                    'data' => $values,
                    'borderColor' => 'rgb(0, 0, 0)',
                    'backgroundColor' => 'rgba(0, 0, 0, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgb(0, 0, 0)',
                    'pointBorderColor' => 'rgb(255, 255, 255)',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
