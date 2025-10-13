<?php

namespace App\Filament\Widgets;

use App\Models\DriverLocationLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentDriverLocations extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DriverLocationLog::query()
                    ->with('driver')
                    ->where('created_at', '>=', now()->subHours(24))
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coordinates')
                    ->label('Location')
                    ->formatStateUsing(fn ($record) => number_format($record->latitude, 4).', '.number_format($record->longitude, 4)),
                Tables\Columns\TextColumn::make('daily_distance_km')
                    ->label('Daily Distance')
                    ->suffix(' km')
                    ->placeholder('0'),
                Tables\Columns\TextColumn::make('monthly_distance_km')
                    ->label('Monthly Distance')
                    ->suffix(' km')
                    ->placeholder('0'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_on_map')
                    ->label('Map')
                    ->icon('heroicon-o-map-pin')
                    ->color('info')
                    ->url(fn (DriverLocationLog $record) => "https://www.google.com/maps/place/{$record->latitude},{$record->longitude}/@{$record->latitude},{$record->longitude},15z")
                    ->openUrlInNewTab(),
            ])
            ->heading('Recent Driver Locations (Last 24 Hours)')
            ->description('Latest location updates from active drivers');
    }
}
