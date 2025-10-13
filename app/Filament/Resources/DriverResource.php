<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Models\Driver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Drivers';

    protected static ?string $modelLabel = 'Driver';

    protected static ?string $pluralModelLabel = 'Drivers';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Driver Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('license_number')
                                    ->label('License Number')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('device_id')
                                    ->label('Device ID')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('vehicle_number')
                                    ->label('Vehicle Number')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\TextInput::make('vehicle_number_plate')
                            ->label('Vehicle Number Plate')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Document Verification')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('uber_screenshot')
                                    ->label('Uber App Screenshot')
                                    ->image()
                                    ->maxSize(5120),
                                Forms\Components\FileUpload::make('identity_document')
                                    ->label('Identity Document')
                                    ->image()
                                    ->maxSize(5120),
                            ]),
                        Forms\Components\Select::make('verification_status')
                            ->label('Verification Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'under_review' => 'Under Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('documents_uploaded_at')
                                    ->label('Documents Uploaded At')
                                    ->disabled(),
                                Forms\Components\DateTimePicker::make('verified_at')
                                    ->label('Verified At')
                                    ->disabled(),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Location Tracking')
                    ->schema([
                        Forms\Components\Placeholder::make('current_location_map')
                            ->label('Current Location')
                            ->content(function ($record) {
                                if (! $record || ! $record->current_latitude || ! $record->current_longitude) {
                                    return '<div class="text-gray-500 italic">No location data available</div>';
                                }

                                $lat = $record->current_latitude;
                                $lng = $record->current_longitude;
                                $lastUpdate = $record->last_location_update
                                    ? $record->last_location_update->diffForHumans()
                                    : 'Never';

                                return '
                                    <div class="space-y-3">
                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-gray-700">Live Location</span>
                                                <span class="text-sm text-gray-500">Updated: '.$lastUpdate.'</span>
                                            </div>
                                            <div class="text-sm text-gray-600 mb-3">
                                                📍 '.number_format($lat, 6).', '.number_format($lng, 6).'
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="https://www.google.com/maps?q='.$lat.','.$lng.'&z=15" 
                                                   target="_blank" 
                                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                                    🗺️ View on Google Maps
                                                </a>
                                                <a href="javascript:void(0)" 
                                                   onclick="navigator.clipboard.writeText(\''.$lat.','.$lng.'\')" 
                                                   class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition-colors">
                                                    📋 Copy Coordinates
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('daily_distance_km')
                                    ->label('Daily Distance (KM)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->disabled()
                                    ->dehydrated(false),
                                Forms\Components\DateTimePicker::make('last_location_update')
                                    ->label('Last Location Update')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        Forms\Components\Placeholder::make('location_stats')
                            ->label('Location Summary')
                            ->content(function ($record) {
                                if (! $record) {
                                    return 'No location data available';
                                }

                                $locationLogs = $record->locationLogs()->count();
                                $lastUpdate = $record->last_location_update
                                    ? $record->last_location_update->diffForHumans()
                                    : 'Never';

                                return "Total Location Logs: {$locationLogs} | Last Update: {$lastUpdate}";
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Driver Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('device_id')
                    ->label('Device ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_number')
                    ->label('Vehicle')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('verification_status')
                    ->label('Verification')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'under_review',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Pending',
                            'under_review' => 'Under Review',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            default => 'Unknown',
                        };
                    }),
                Tables\Columns\TextColumn::make('last_location_update')
                    ->label('Last Location')
                    ->dateTime('M j, g:i A')
                    ->sortable()
                    ->placeholder('Never'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('verification_status')
                    ->label('Verification Status')
                    ->options([
                        'pending' => 'Pending Review',
                        'under_review' => 'Under Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('has_location')
                    ->label('Has Location Data')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('current_latitude')
                            ->whereNotNull('current_longitude'),
                        false: fn (Builder $query) => $query->whereNull('current_latitude')
                            ->orWhereNull('current_longitude'),
                    ),
                Tables\Filters\TernaryFilter::make('has_documents')
                    ->label('Has Documents')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('uber_screenshot')
                            ->whereNotNull('identity_document'),
                        false: fn (Builder $query) => $query->where(function ($query) {
                            $query->whereNull('uber_screenshot')
                                ->orWhereNull('identity_document');
                        }),
                    ),
                Tables\Filters\Filter::make('recent_location')
                    ->label('Recently Active (Last 2 Hours)')
                    ->query(fn (Builder $query) => $query->where('last_location_update', '>=', now()->subHours(2))),
            ])
            ->actions([
                Tables\Actions\Action::make('view_location')
                    ->label('View Location')
                    ->icon('heroicon-o-map-pin')
                    ->color('info')
                    ->url(fn (Driver $record) => $record->current_latitude && $record->current_longitude
                        ? "https://www.google.com/maps/@{$record->current_latitude},{$record->current_longitude},15z"
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn (Driver $record) => $record->current_latitude && $record->current_longitude),

                Tables\Actions\Action::make('location_history')
                    ->label('Location History')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->modalContent(function (Driver $record) {
                        $recentLogs = $record->locationLogs()
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();

                        if ($recentLogs->isEmpty()) {
                            return view('filament.driver-location-modal', [
                                'logs' => [],
                                'message' => 'No location history available.',
                            ]);
                        }

                        return view('filament.driver-location-modal', ['logs' => $recentLogs]);
                    })
                    ->modalWidth('2xl'),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Driver tracking system - show total count of active drivers
        $count = static::getModel()::where('is_active', true)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
