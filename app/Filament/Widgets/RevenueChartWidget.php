<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue & Spending';

    protected static ?int $sort = 1;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    protected int $daysOfHistory = 30;

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
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.dataset.label || "";
                            let value = context.parsed.y;
                            if (label) {
                                label += ": ";
                            }
                            return label + new Intl.NumberFormat("en-US", {
                                style: "currency",
                                currency: "USD"
                            }).format(value);
                        }',
                    ],
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
                        'callback' => '(value) => "$" + value.toLocaleString()',
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
            ],
            'elements' => [
                'line' => [
                    'tension' => 0.25,
                ],
                'point' => [
                    'radius' => 3,
                    'hoverRadius' => 5,
                ],
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
        $dates = collect();
        $revenueData = collect();
        $spendingData = collect();

        for ($days_backwards = $this->daysOfHistory - 1; $days_backwards >= 0; $days_backwards--) {
            $date = Carbon::now()->subDays($days_backwards)->format('Y-m-d');

            // Group by date for better performance
            $dates->push(Carbon::parse($date)->format('M d'));

            // Revenue (deposits)
            $revenue = Payment::whereDate('created_at', $date)
                ->where('type', Payment::TYPE_DEPOSIT)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');

            $revenueData->push(round($revenue, 2));

            // Ad spending
            $spending = Payment::whereDate('created_at', $date)
                ->where('type', Payment::TYPE_AD_SPEND)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');

            $spendingData->push(round(abs($spending), 2));
        }

        // Calculate moving averages for smoother trends
        $revenueTrend = $this->calculateMovingAverage($revenueData, 5);
        $spendingTrend = $this->calculateMovingAverage($spendingData, 5);

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueData->toArray(),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'pointBackgroundColor' => '#22c55e',
                ],
                [
                    'label' => 'Ad Spending',
                    'data' => $spendingData->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.05)',
                    'fill' => true,
                    'pointBackgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Revenue Trend',
                    'data' => $revenueTrend->toArray(),
                    'borderColor' => '#22c55e',
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                    'pointRadius' => 0,
                    'fill' => false,
                ],
                [
                    'label' => 'Spending Trend',
                    'data' => $spendingTrend->toArray(),
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                    'pointRadius' => 0,
                    'fill' => false,
                ],
            ],
            'labels' => $dates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function calculateMovingAverage($data, $window)
    {
        $result = collect();
        $dataArray = $data->toArray();
        $count = count($dataArray);

        for ($i = 0; $i < $count; $i++) {
            $start = max(0, $i - floor($window / 2));
            $end = min($count - 1, $i + floor($window / 2));
            $windowSize = $end - $start + 1;
            $windowSum = 0;

            for ($j = $start; $j <= $end; $j++) {
                $windowSum += $dataArray[$j];
            }

            $result->push(round($windowSum / $windowSize, 2));
        }

        return $result;
    }
}
