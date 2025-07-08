<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImpressionResource\Pages;
use App\Filament\Resources\ImpressionResource\RelationManagers;
use App\Models\Impression;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImpressionResource extends Resource
{
    protected static ?string $model = Impression::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';
    
    protected static ?string $navigationGroup = 'Campaign Management';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $navigationLabel = 'Impressions & Analytics';
    
    protected static ?string $modelLabel = 'Impression';
    
    protected static ?string $pluralModelLabel = 'Impressions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Impression Details')
                    ->schema([
                        Forms\Components\Select::make('ad_id')
                            ->relationship('ad', 'campaign_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options(Impression::getTypes()),
                        Forms\Components\TextInput::make('ip_address')
                            ->maxLength(45)
                            ->placeholder('User IP address'),
                        Forms\Components\Textarea::make('user_agent')
                            ->placeholder('Browser user agent')
                            ->maxLength(500),
                        Forms\Components\TextInput::make('cost')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.0001)
                            ->default(0),
                        Forms\Components\DateTimePicker::make('viewed_at')
                            ->required()
                            ->default(now()),
                    ])->columns(2),
                
                Forms\Components\Section::make('Location & Device Info')
                    ->schema([
                        Forms\Components\Textarea::make('location_data')
                            ->placeholder('JSON data for location (GPS, city, etc.)')
                            ->helperText('Example: {"lat": 40.7128, "lng": -74.0060, "city": "New York"}'),
                        Forms\Components\Textarea::make('device_info')
                            ->placeholder('JSON data for device information')
                            ->helperText('Example: {"device": "mobile", "os": "iOS", "browser": "Safari"}'),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('viewed_at')
                    ->label('Date & Time')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ad.campaign_name')
                    ->label('Campaign')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(30),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(20),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => Impression::TYPE_DISPLAY,
                        'success' => Impression::TYPE_QR_SCAN,
                    ])
                    ->formatStateUsing(fn (string $state): string => Impression::getTypes()[$state] ?? $state),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Cost')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_ad_cost')
                    ->label('Total Ad Cost')
                    ->getStateUsing(function (Impression $record): string {
                        $totalCost = $record->ad->impressions()->sum('cost');
                        return '$' . number_format($totalCost, 4);
                    })
                    ->alignEnd()
                    ->color('info'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location_data')
                    ->label('Location')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state) && isset($state['city'])) {
                            return $state['city'];
                        }
                        return '—';
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('device_info')
                    ->label('Device')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state) && isset($state['device'])) {
                            return ucfirst($state['device']);
                        }
                        return '—';
                    })
                    ->toggleable(),
            ])
            ->defaultSort('viewed_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(Impression::getTypes())
                    ->multiple(),
                Tables\Filters\SelectFilter::make('ad_id')
                    ->relationship('ad', 'campaign_name')
                    ->label('Campaign')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('viewed_at')
                    ->form([
                        Forms\Components\DatePicker::make('viewed_from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('viewed_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['viewed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('viewed_at', '>=', $date),
                            )
                            ->when(
                                $data['viewed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('viewed_at', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('cost_range')
                    ->form([
                        Forms\Components\TextInput::make('cost_from')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('cost_to')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['cost_from'],
                                fn (Builder $query, $cost): Builder => $query->where('cost', '>=', $cost),
                            )
                            ->when(
                                $data['cost_to'],
                                fn (Builder $query, $cost): Builder => $query->where('cost', '<=', $cost),
                            );
                    }),
                Tables\Filters\Filter::make('qr_scans_only')
                    ->label('QR Scans Only')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('type', Impression::TYPE_QR_SCAN)
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('boost_impressions')
                    ->label('Boost Impressions')
                    ->icon('heroicon-o-arrow-trending-up')
                    ->color('success')
                    ->visible(fn (Impression $record): bool => $record->type === Impression::TYPE_DISPLAY)
                    ->form([
                        Forms\Components\TextInput::make('boost_count')
                            ->label('Number of additional impressions')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(1000),
                        Forms\Components\Textarea::make('boost_reason')
                            ->label('Reason for boost')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (Impression $record, array $data): void {
                        // Here you would implement the logic to create additional impressions
                        // For now, we'll just show a notification
                        \Filament\Notifications\Notification::make()
                            ->title('Impressions Boosted')
                            ->body("Added {$data['boost_count']} impressions to the campaign.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_analytics')
                        ->label('Export Analytics')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('primary')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            // Here you would implement CSV/Excel export logic
                            \Filament\Notifications\Notification::make()
                                ->title('Analytics Export')
                                ->body('Analytics data has been exported.')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->emptyStateHeading('No impressions found')
            ->emptyStateDescription('All ad impressions and QR scans will appear here.')
            ->emptyStateIcon('heroicon-o-eye')
            ->striped()
            ->paginated([25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImpressions::route('/'),
            'create' => Pages\CreateImpression::route('/create'),
            'edit' => Pages\EditImpression::route('/{record}/edit'),
        ];
    }
}
