<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ad_id')
                    ->relationship('ad', 'campaign_name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select an ad (optional)'),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(Transaction::getTypes()),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(Transaction::getStatuses()),
                Forms\Components\TextInput::make('reference')
                    ->maxLength(255)
                    ->placeholder('Payment ID, transaction reference, etc.'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ad.campaign_name')
                    ->label('Campaign')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->placeholder('—')
                    ->limit(20),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => Transaction::TYPE_DEPOSIT,
                        'success' => Transaction::TYPE_BONUS,
                        'danger' => Transaction::TYPE_AD_SPEND,
                        'secondary' => Transaction::TYPE_REFUND,
                    ])
                    ->formatStateUsing(fn (string $state): string => Transaction::getTypes()[$state] ?? $state),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable()
                    ->color(fn (Transaction $record): string => 
                        $record->amount < 0 ? 'danger' : 'success'
                    ),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => Transaction::STATUS_PENDING,
                        'success' => Transaction::STATUS_COMPLETED,
                        'danger' => Transaction::STATUS_FAILED,
                    ])
                    ->formatStateUsing(fn (string $state): string => Transaction::getStatuses()[$state] ?? $state),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->wrap(),
                Tables\Columns\TextColumn::make('reference')
                    ->label('Ref')
                    ->limit(10)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(Transaction::getTypes())
                    ->multiple(),
                Tables\Filters\SelectFilter::make('status')
                    ->options(Transaction::getStatuses())
                    ->multiple(),
                Tables\Filters\Filter::make('amount_range')
                    ->form([
                        Forms\Components\TextInput::make('amount_from')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('amount_to')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['amount_from'],
                                fn (Builder $query, $amount): Builder => $query->where('amount', '>=', $amount),
                            )
                            ->when(
                                $data['amount_to'],
                                fn (Builder $query, $amount): Builder => $query->where('amount', '<=', $amount),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No transactions found')
            ->emptyStateDescription('All user transactions will appear here.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
