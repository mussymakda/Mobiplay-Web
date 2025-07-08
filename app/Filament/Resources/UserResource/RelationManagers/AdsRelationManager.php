<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Ad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdsRelationManager extends RelationManager
{
    protected static string $relationship = 'ads';

    protected static ?string $title = 'All Campaigns';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('campaign_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        Ad::STATUS_ACTIVE => 'Active',
                        Ad::STATUS_PAUSED => 'Paused',
                        Ad::STATUS_COMPLETED => 'Completed',
                        Ad::STATUS_REJECTED => 'Rejected',
                    ]),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('budget')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('spent')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('campaign_name')
            ->columns([
                Tables\Columns\TextColumn::make('campaign_name')
                    ->label('Campaign')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => Ad::STATUS_ACTIVE,
                        'warning' => Ad::STATUS_PAUSED,
                        'info' => Ad::STATUS_COMPLETED,
                        'danger' => Ad::STATUS_REJECTED,
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('spent')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('impressions')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->label('Impressions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('qr_scans')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->label('QR Scans')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Ad::STATUS_ACTIVE => 'Active',
                        Ad::STATUS_PAUSED => 'Paused',
                        Ad::STATUS_COMPLETED => 'Completed',
                        Ad::STATUS_REJECTED => 'Rejected',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('package_id')
                    ->relationship('package', 'name')
                    ->label('Package')
                    ->preload()
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Campaign'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pause')
                    ->label('Pause')
                    ->icon('heroicon-o-pause')
                    ->color('warning')
                    ->visible(fn (Ad $record) => $record->status === Ad::STATUS_ACTIVE)
                    ->action(fn (Ad $record) => $record->update(['status' => Ad::STATUS_PAUSED])),
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn (Ad $record) => $record->status === Ad::STATUS_PAUSED)
                    ->action(fn (Ad $record) => $record->update(['status' => Ad::STATUS_ACTIVE])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('pauseBulk')
                        ->label('Pause Selected')
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->action(fn (Builder $query) => $query->update(['status' => Ad::STATUS_PAUSED]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('activateBulk')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-play')
                        ->color('success')
                        ->action(fn (Builder $query) => $query->update(['status' => Ad::STATUS_ACTIVE]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('No campaigns found')
            ->emptyStateDescription('This user has no campaigns yet.')
            ->emptyStateIcon('heroicon-o-rectangle-stack')
            ->paginated([10, 25, 50]);
    }
}
