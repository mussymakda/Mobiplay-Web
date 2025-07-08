<?php

namespace App\Filament\Widgets;

use App\Models\Ad;
use App\Models\Impression;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PerformanceChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Performance Trends';
    
    protected static ?int $sort = 4;
    
    protected function getData(): array
    {
        $days = 14; // Show data for the last 14 days
        $labels = [];
        $impressionData = [];
        $qrScanData = [];
        $revenueData = [];
        
        // Generate dates for the chart
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');
            
            // Get impression counts
            $impressionCount = Impression::whereDate('created_at', $date->format('Y-m-d'))
                ->where('type', Impression::TYPE_DISPLAY)
                ->count();
            $impressionData[] = $impressionCount;
            
            // Get QR scan counts
            $qrScanCount = Impression::whereDate('created_at', $date->format('Y-m-d'))
                ->where('type', Impression::TYPE_QR_SCAN)
                ->count();
            $qrScanData[] = $qrScanCount;
            
            // Get revenue (deposits)
            $dailyRevenue = Payment::whereDate('created_at', $date->format('Y-m-d'))
                ->where('type', Payment::TYPE_DEPOSIT)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');
            $revenueData[] = round($dailyRevenue, 2);
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Impressions',
                    'data' => $impressionData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.2,
                ],
                [
                    'label' => 'QR Scans',
                    'data' => $qrScanData,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.2,
                ],
                [
                    'label' => 'Revenue ($)',
                    'data' => $revenueData,
                    'borderColor' => 'rgb(249, 115, 22)',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.1)',
                    'fill' => true,
                    'tension' => 0.2,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Count',
                    ],
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue ($)',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'radius' => 3,
                    'hoverRadius' => 5,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
        ];
    }
}
