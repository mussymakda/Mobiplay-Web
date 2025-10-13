<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverLocationLogResource\Pages;
use App\Models\DriverLocationLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DriverLocationLogResource extends Resource
{
    protected static ?string $model = DriverLocationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Driver Locations';

    protected static ?string $modelLabel = 'Location Log';

    protected static ?string $pluralModelLabel = 'Location Logs';

    protected static ?string $navigationGroup = 'Driver Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Location Information')
                    ->schema([
                        Forms\Components\Select::make('driver_id')
                            ->relationship('driver', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->numeric()
                                    ->required()
                                    ->step(0.0000001),
                                Forms\Components\TextInput::make('longitude')
                                    ->numeric()
                                    ->required()
                                    ->step(0.0000001),
                            ]),

                        Forms\Components\DateTimePicker::make('recorded_at')
                            ->default(now()),
                    ]),

                Forms\Components\Section::make('Distance Tracking')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('daily_distance_km')
                                    ->label('Daily Distance')
                                    ->numeric()
                                    ->suffix('km')
                                    ->disabled(),
                                Forms\Components\TextInput::make('monthly_distance_km')
                                    ->label('Monthly Distance')
                                    ->numeric()
                                    ->suffix('km')
                                    ->disabled(),
                            ]),
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Additional Data')
                            ->keyLabel('Key')
                            ->valueLabel('Value'),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recorded_at')
                    ->label('Date & Time')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                Tables\Columns\ViewColumn::make('location_map')
                    ->label('Location')
                    ->view('filament.location-map-preview')
                    ->width(200),
                Tables\Columns\TextColumn::make('daily_distance_km')
                    ->label('Daily Distance')
                    ->suffix(' km')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('monthly_distance_km')
                    ->label('Monthly Distance')
                    ->suffix(' km')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('driver')
                    ->relationship('driver', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('recorded_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('recorded_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('recorded_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_on_map')
                    ->label('View on Map')
                    ->icon('heroicon-o-map-pin')
                    ->color('info')
                    ->url(fn (DriverLocationLog $record) => "https://www.google.com/maps/place/{$record->latitude},{$record->longitude}/@{$record->latitude},{$record->longitude},15z")
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('recorded_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDriverLocationLogs::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $todayCount = static::getModel()::whereDate('created_at', today())->count();

        return $todayCount > 0 ? (string) $todayCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
