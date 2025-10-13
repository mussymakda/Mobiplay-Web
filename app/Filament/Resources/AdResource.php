<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\InteractiveMap;
use App\Filament\Resources\AdResource\Pages;
use App\Models\Ad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Campaign Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Campaign Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Select the advertiser for this campaign'),
                        Forms\Components\TextInput::make('campaign_name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Summer Sale Campaign')
                            ->helperText('Give your campaign a descriptive name'),
                        Forms\Components\Select::make('package_id')
                            ->relationship('package', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Choose the advertising package'),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                Ad::STATUS_PENDING => 'Pending Review',
                                Ad::STATUS_ACTIVE => 'Active',
                                Ad::STATUS_PAUSED => 'Paused',
                                Ad::STATUS_COMPLETED => 'Completed',
                                Ad::STATUS_REJECTED => 'Rejected',
                            ])
                            ->default(Ad::STATUS_PENDING),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Content')
                    ->schema([
                        Forms\Components\Select::make('media_type')
                            ->required()
                            ->options([
                                Ad::MEDIA_TYPE_IMAGE => 'Image',
                                Ad::MEDIA_TYPE_VIDEO => 'Video',
                            ])
                            ->default(Ad::MEDIA_TYPE_IMAGE)
                            ->live(),
                        Forms\Components\FileUpload::make('media_path')
                            ->label('Upload Media')
                            ->directory('ads')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(10240) // 10MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm'])
                            ->helperText('Upload your advertisement image or video (max 10MB)'),
                        Forms\Components\TextInput::make('cta_url')
                            ->label('Call-to-Action URL')
                            ->url()
                            ->placeholder('https://example.com/landing-page')
                            ->helperText('URL that users will visit when they scan the QR code'),
                        Forms\Components\TextInput::make('qr_code_url')
                            ->label('Generated QR Code URL')
                            ->disabled()
                            ->helperText('QR code will be generated automatically'),
                    ])->columns(1),

                Forms\Components\Section::make('Location & Targeting')
                    ->schema([
                        Forms\Components\TextInput::make('location_name')
                            ->label('Location Name')
                            ->placeholder('e.g., Downtown Mall, City Center')
                            ->helperText('Descriptive name for the target location'),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->numeric()
                                    ->step(0.000001)
                                    ->placeholder('40.7128')
                                    ->helperText('Click on map to auto-fill'),
                                Forms\Components\TextInput::make('longitude')
                                    ->numeric()
                                    ->step(0.000001)
                                    ->placeholder('-74.0060')
                                    ->helperText('Click on map to auto-fill'),
                                Forms\Components\TextInput::make('radius_miles')
                                    ->label('Radius (Miles)')
                                    ->numeric()
                                    ->suffix('mi')
                                    ->default(5)
                                    ->minValue(0.5)
                                    ->maxValue(50)
                                    ->helperText('Ad display radius in miles'),
                            ]),
                        InteractiveMap::make('location_map')
                            ->label('Select Target Location')
                            ->helperText('Click on the map to set the target location for your ad campaign. You can also drag the marker or use the search feature.'),
                    ])->columns(1),

                Forms\Components\Section::make('Budget & Performance')
                    ->schema([
                        Forms\Components\TextInput::make('daily_budget')
                            ->label('Daily Ad Spend')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->default(10.00)
                            ->helperText('Maximum amount to spend per day on this campaign'),
                        Forms\Components\TextInput::make('budget')
                            ->label('Total Budget (Legacy)')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->default(0.00)
                            ->helperText('Legacy total budget field - leave as 0 for daily budget campaigns'),
                        Forms\Components\TextInput::make('spent')
                            ->label('Total Amount Spent')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->default(0.00)
                            ->disabled()
                            ->helperText('Total amount spent across all days'),
                        Forms\Components\TextInput::make('daily_spent')
                            ->label('Daily Amount Spent')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->default(0.00)
                            ->disabled()
                            ->helperText('Amount spent today (resets daily)'),
                        Forms\Components\TextInput::make('impressions')
                            ->label('Total Impressions')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->helperText('Number of times ad was displayed'),
                        Forms\Components\TextInput::make('qr_scans')
                            ->label('QR Code Scans')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->helperText('Number of QR code scans'),
                    ])->columns(2),

                Forms\Components\Section::make('Approval Management')
                    ->schema([
                        Forms\Components\Select::make('reviewed_by')
                            ->label('Reviewed By')
                            ->relationship('reviewer', 'name')
                            ->disabled()
                            ->placeholder('Not yet reviewed'),

                        Forms\Components\DateTimePicker::make('submitted_for_review_at')
                            ->label('Submitted for Review')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('reviewed_at')
                            ->label('Review Date')
                            ->disabled(),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->placeholder('Add notes about this ad review')
                            ->rows(3),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->placeholder('Specify why this ad was rejected')
                            ->rows(2)
                            ->visible(fn ($record) => $record?->isRejected()),

                        Forms\Components\TagsInput::make('content_flags')
                            ->label('Content Flags')
                            ->placeholder('Add content moderation flags')
                            ->suggestions([
                                'inappropriate_content',
                                'misleading_claims',
                                'poor_quality_media',
                                'trademark_violation',
                                'spam_content',
                                'adult_content',
                                'violence',
                                'hate_speech',
                            ])
                            ->visible(fn ($record) => $record?->isContentFlagged()),

                        Forms\Components\TextInput::make('revision_count')
                            ->label('Revision Count')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\Toggle::make('auto_approved')
                            ->label('Auto Approved')
                            ->disabled(),

                        Forms\Components\TextInput::make('auto_approval_reason')
                            ->label('Auto Approval Reason')
                            ->disabled()
                            ->visible(fn ($record) => $record?->auto_approved),
                    ])
                    ->columns(2)
                    ->visible(fn ($context) => $context === 'edit' || $context === 'view'),

                Forms\Components\Section::make('Approval History')
                    ->schema([
                        Forms\Components\KeyValue::make('approval_history')
                            ->label('History')
                            ->disabled()
                            ->keyLabel('Timestamp')
                            ->valueLabel('Action')
                            ->formatStateUsing(function ($state) {
                                if (! $state) {
                                    return [];
                                }

                                $formatted = [];
                                foreach ($state as $entry) {
                                    $timestamp = isset($entry['timestamp']) ? date('M j, Y g:i A', strtotime($entry['timestamp'])) : 'Unknown';
                                    $action = $entry['action'] ?? 'Unknown';
                                    $admin = $entry['admin_name'] ?? 'System';
                                    $details = $entry['details'] ?? '';

                                    $formatted[$timestamp] = "{$action} by {$admin}: {$details}";
                                }

                                return $formatted;
                            }),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->visible(fn ($record) => $record && ! empty($record->approval_history)),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('campaign_name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Advertiser')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => Ad::STATUS_PENDING,
                        'success' => Ad::STATUS_ACTIVE,
                        'secondary' => Ad::STATUS_PAUSED,
                        'primary' => Ad::STATUS_COMPLETED,
                        'danger' => Ad::STATUS_REJECTED,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Ad::STATUS_PENDING => 'Pending',
                        Ad::STATUS_ACTIVE => 'Active',
                        Ad::STATUS_PAUSED => 'Paused',
                        Ad::STATUS_COMPLETED => 'Completed',
                        Ad::STATUS_REJECTED => 'Rejected',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Reviewed By')
                    ->placeholder('Not reviewed')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('reviewed_at')
                    ->label('Review Date')
                    ->dateTime()
                    ->placeholder('Not reviewed')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('content_flagged')
                    ->label('Flagged')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('revision_count')
                    ->label('Revisions')
                    ->badge()
                    ->color(fn (int $state): string => $state > 2 ? 'danger' : ($state > 0 ? 'warning' : 'success'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('media_type')
                    ->colors([
                        'primary' => Ad::MEDIA_TYPE_IMAGE,
                        'success' => Ad::MEDIA_TYPE_VIDEO,
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->limit(20),
                Tables\Columns\TextColumn::make('location_name')
                    ->label('Location')
                    ->limit(25)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('radius_km')
                    ->label('Radius')
                    ->suffix(' km')
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('daily_budget')
                    ->label('Daily Budget')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daily_spent')
                    ->label('Today Spent')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable()
                    ->color(fn (Ad $record): string => $record->daily_spent >= $record->daily_budget ? 'danger' :
                        ($record->daily_spent >= ($record->daily_budget * 0.8) ? 'warning' : 'success')
                    ),
                Tables\Columns\TextColumn::make('remaining_daily_budget')
                    ->label('Daily Remaining')
                    ->money('USD')
                    ->alignEnd()
                    ->color(fn (Ad $record): string => $record->remaining_daily_budget <= 0 ? 'danger' :
                        ($record->remaining_daily_budget < ($record->daily_budget * 0.2) ? 'warning' : 'success')
                    ),
                Tables\Columns\TextColumn::make('impressions')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qr_scans')
                    ->label('QR Scans')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qr_scan_rate')
                    ->label('Scan Rate')
                    ->formatStateUsing(fn (Ad $record): string => $record->qr_scan_rate.'%')
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Ad::STATUS_PENDING => 'Pending',
                        Ad::STATUS_ACTIVE => 'Active',
                        Ad::STATUS_PAUSED => 'Paused',
                        Ad::STATUS_COMPLETED => 'Completed',
                        Ad::STATUS_REJECTED => 'Rejected',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('media_type')
                    ->options([
                        Ad::MEDIA_TYPE_IMAGE => 'Image',
                        Ad::MEDIA_TYPE_VIDEO => 'Video',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Advertiser')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('package_id')
                    ->relationship('package', 'name')
                    ->label('Package')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('budget_range')
                    ->form([
                        Forms\Components\TextInput::make('budget_from')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('budget_to')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['budget_from'],
                                fn (Builder $query, $amount): Builder => $query->where('budget', '>=', $amount),
                            )
                            ->when(
                                $data['budget_to'],
                                fn (Builder $query, $amount): Builder => $query->where('budget', '<=', $amount),
                            );
                    }),
                Tables\Filters\Filter::make('low_budget')
                    ->label('Low Budget (<20% remaining)')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('(budget - spent) < (budget * 0.2)')
                    ),
                Tables\Filters\Filter::make('high_performance')
                    ->label('High QR Scan Rate (>5%)')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('impressions > 0 AND (qr_scans / impressions) > 0.05')
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\Textarea::make('admin_notes')
                                ->label('Approval Notes (Optional)')
                                ->placeholder('Add any notes about this approval...'),
                        ])
                        ->action(function (Ad $record, array $data) {
                            $admin = auth('admin')->user();
                            $record->approve($admin, $data['admin_notes'] ?? null);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Approve Advertisement')
                        ->modalDescription('This will approve the ad and make it eligible to go live.')
                        ->visible(fn (Ad $record): bool => $record->isPending()),

                    Tables\Actions\Action::make('reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->placeholder('Please specify why this ad is being rejected...'),
                            Forms\Components\Textarea::make('admin_notes')
                                ->label('Additional Notes (Optional)')
                                ->placeholder('Add any additional feedback...'),
                        ])
                        ->action(function (Ad $record, array $data) {
                            $admin = auth('admin')->user();
                            $record->reject($admin, $data['rejection_reason'], $data['admin_notes'] ?? null);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Reject Advertisement')
                        ->modalDescription('This will reject the ad and notify the advertiser.')
                        ->visible(fn (Ad $record): bool => $record->isPending()),

                    Tables\Actions\Action::make('flag_content')
                        ->icon('heroicon-o-flag')
                        ->color('warning')
                        ->form([
                            Forms\Components\CheckboxList::make('content_flags')
                                ->label('Content Issues')
                                ->required()
                                ->options([
                                    'inappropriate_content' => 'Inappropriate Content',
                                    'misleading_claims' => 'Misleading Claims',
                                    'poor_quality_media' => 'Poor Quality Media',
                                    'trademark_violation' => 'Trademark Violation',
                                    'spam_content' => 'Spam Content',
                                    'adult_content' => 'Adult Content',
                                    'violence' => 'Violence',
                                    'hate_speech' => 'Hate Speech',
                                ])
                                ->columns(2),
                            Forms\Components\Textarea::make('admin_notes')
                                ->label('Notes')
                                ->placeholder('Explain the content issues...'),
                        ])
                        ->action(function (Ad $record, array $data) {
                            $admin = auth('admin')->user();
                            $record->flagContent($data['content_flags'], $admin, $data['admin_notes'] ?? null);
                        })
                        ->modalHeading('Flag Content for Review')
                        ->visible(fn (Ad $record): bool => ! $record->isRejected()),

                    Tables\Actions\Action::make('activate')
                        ->icon('heroicon-o-play')
                        ->color('success')
                        ->action(fn (Ad $record) => $record->update(['status' => Ad::STATUS_ACTIVE]))
                        ->requiresConfirmation()
                        ->modalHeading('Activate Campaign')
                        ->modalDescription('This will make the ad live and start spending budget.')
                        ->visible(fn (Ad $record): bool => in_array($record->status, [Ad::STATUS_PAUSED]) && $record->isApproved()),

                    Tables\Actions\Action::make('pause')
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->action(fn (Ad $record) => $record->update(['status' => Ad::STATUS_PAUSED]))
                        ->requiresConfirmation()
                        ->modalHeading('Pause Campaign')
                        ->modalDescription('This will pause the ad and stop spending budget.')
                        ->visible(fn (Ad $record): bool => $record->status === Ad::STATUS_ACTIVE),

                    Tables\Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate_selected')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-play')
                        ->action(fn ($records) => $records->each->update(['status' => Ad::STATUS_ACTIVE]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('pause_selected')
                        ->label('Pause Selected')
                        ->icon('heroicon-o-pause')
                        ->action(fn ($records) => $records->each->update(['status' => Ad::STATUS_PAUSED]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No ads found')
            ->emptyStateDescription('Start by creating your first advertisement campaign.')
            ->emptyStateIcon('heroicon-o-megaphone')
            ->striped()
            ->paginated([10, 25, 50]);
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
            'index' => Pages\ListAds::route('/'),
            'create' => Pages\CreateAd::route('/create'),
            'edit' => Pages\EditAd::route('/{record}/edit'),
        ];
    }
}
