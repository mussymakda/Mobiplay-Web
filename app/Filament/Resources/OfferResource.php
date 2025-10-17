<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Models\Offer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Financial Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Offer Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Welcome Bonus, Reload Offer'),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                Offer::TYPE_FIRST_DEPOSIT => 'First Deposit',
                                Offer::TYPE_RELOAD_BONUS => 'Reload Bonus',
                                Offer::TYPE_PERCENTAGE_BONUS => 'Percentage Bonus',
                                Offer::TYPE_FIXED_BONUS => 'Fixed Bonus',
                            ])
                            ->default(Offer::TYPE_FIRST_DEPOSIT),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->helperText('Whether this offer is currently available to users'),
                        Forms\Components\Textarea::make('description')
                            ->placeholder('Brief description of the offer')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Bonus Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('bonus_percentage')
                            ->label('Bonus Percentage (%)')
                            ->numeric()
                            ->suffix('%')
                            ->step(0.01)
                            ->placeholder('e.g., 100 for 100%')
                            ->helperText('Leave empty if using fixed amount'),
                        Forms\Components\TextInput::make('bonus_fixed_amount')
                            ->label('Fixed Bonus Amount')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->placeholder('e.g., 50.00')
                            ->helperText('Leave empty if using percentage'),
                        Forms\Components\TextInput::make('minimum_deposit')
                            ->label('Minimum Deposit Required')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->placeholder('e.g., 25.00'),
                        Forms\Components\TextInput::make('maximum_bonus')
                            ->label('Maximum Bonus Amount')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->placeholder('e.g., 500.00')
                            ->helperText('Cap on bonus amount (for percentage-based offers)'),
                    ])->columns(2),

                Forms\Components\Section::make('Validity & Usage')
                    ->schema([
                        Forms\Components\DateTimePicker::make('valid_from')
                            ->required()
                            ->default(now())
                            ->label('Valid From'),
                        Forms\Components\DateTimePicker::make('valid_until')
                            ->required()
                            ->label('Valid Until'),
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Usage Limit')
                            ->numeric()
                            ->placeholder('Leave empty for unlimited')
                            ->helperText('Maximum number of times this offer can be used'),
                        Forms\Components\TextInput::make('used_count')
                            ->label('Times Used')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->helperText('Automatically tracked'),
                    ])->columns(2),

                Forms\Components\Section::make('Terms & Conditions')
                    ->schema([
                        Forms\Components\Textarea::make('conditions')
                            ->label('Terms and Conditions')
                            ->placeholder('Detailed terms and conditions for this offer')
                            ->rows(4),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => Offer::TYPE_FIRST_DEPOSIT,
                        'success' => Offer::TYPE_RELOAD_BONUS,
                        'warning' => Offer::TYPE_PERCENTAGE_BONUS,
                        'secondary' => Offer::TYPE_FIXED_BONUS,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Offer::TYPE_FIRST_DEPOSIT => 'First Deposit',
                        Offer::TYPE_RELOAD_BONUS => 'Reload Bonus',
                        Offer::TYPE_PERCENTAGE_BONUS => 'Percentage Bonus',
                        Offer::TYPE_FIXED_BONUS => 'Fixed Bonus',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('bonus_percentage')
                    ->label('Bonus %')
                    ->formatStateUsing(fn (?float $state): string => $state ? $state.'%' : '—')
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('bonus_fixed_amount')
                    ->label('Fixed Bonus')
                    ->money('USD')
                    ->alignEnd()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('minimum_deposit')
                    ->label('Min. Deposit')
                    ->money('USD')
                    ->alignEnd()
                    ->toggleable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('maximum_bonus')
                    ->label('Max. Bonus')
                    ->money('USD')
                    ->alignEnd()
                    ->toggleable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('usage_stats')
                    ->label('Usage')
                    ->formatStateUsing(function (Offer $record): string {
                        $used = $record->used_count;
                        $limit = $record->usage_limit;

                        return $limit ? "{$used}/{$limit}" : "{$used}/∞";
                    })
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Expires')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->color(fn (Offer $record): string => $record->valid_until->isPast() ? 'danger' :
                        ($record->valid_until->diffInDays() <= 7 ? 'warning' : 'primary')
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        Offer::TYPE_FIRST_DEPOSIT => 'First Deposit',
                        Offer::TYPE_RELOAD_BONUS => 'Reload Bonus',
                        Offer::TYPE_PERCENTAGE_BONUS => 'Percentage Bonus',
                        Offer::TYPE_FIXED_BONUS => 'Fixed Bonus',
                    ])
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All offers')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                Tables\Filters\Filter::make('valid_offers')
                    ->label('Currently Valid')
                    ->query(fn (Builder $query): Builder => $query->where('valid_until', '>=', now())),
                Tables\Filters\Filter::make('expired_offers')
                    ->label('Expired')
                    ->query(fn (Builder $query): Builder => $query->where('valid_until', '<', now())),
                Tables\Filters\Filter::make('usage_limit')
                    ->label('With Usage Limit')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('usage_limit')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (Offer $record) {
                            $newOffer = $record->replicate();
                            $newOffer->name = $record->name.' (Copy)';
                            $newOffer->used_count = 0;
                            $newOffer->save();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Duplicate Offer')
                        ->modalDescription('This will create a copy of this offer with usage count reset to 0.'),
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
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No offers found')
            ->emptyStateDescription('Create your first offer to start rewarding users with bonuses.')
            ->emptyStateIcon('heroicon-o-gift')
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
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
