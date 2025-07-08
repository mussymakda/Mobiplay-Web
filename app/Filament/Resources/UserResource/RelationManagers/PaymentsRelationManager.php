<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Transaction History';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        Payment::TYPE_DEPOSIT => 'Deposit',
                        Payment::TYPE_AUTO_DEBIT => 'Auto Debit',
                        Payment::TYPE_BONUS => 'Bonus',
                        Payment::TYPE_AD_SPEND => 'Ad Spend',
                        Payment::TYPE_REFUND => 'Refund',
                    ]),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        Payment::STATUS_PENDING => 'Pending',
                        Payment::STATUS_COMPLETED => 'Completed',
                        Payment::STATUS_FAILED => 'Failed',
                        Payment::STATUS_CANCELLED => 'Cancelled',
                    ]),
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
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => Payment::TYPE_DEPOSIT,
                        'warning' => Payment::TYPE_AUTO_DEBIT,
                        'success' => Payment::TYPE_BONUS,
                        'danger' => Payment::TYPE_AD_SPEND,
                        'secondary' => Payment::TYPE_REFUND,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Payment::TYPE_DEPOSIT => 'Deposit',
                        Payment::TYPE_AUTO_DEBIT => 'Auto Debit',
                        Payment::TYPE_BONUS => 'Bonus',
                        Payment::TYPE_AD_SPEND => 'Ad Spend',
                        Payment::TYPE_REFUND => 'Refund',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable()
                    ->color(fn (Payment $record): string => 
                        $record->amount < 0 ? 'danger' : 'success'
                    ),
                Tables\Columns\TextColumn::make('bonus_amount')
                    ->label('Bonus')
                    ->money('USD')
                    ->alignEnd()
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => Payment::STATUS_PENDING,
                        'success' => Payment::STATUS_COMPLETED,
                        'danger' => Payment::STATUS_FAILED,
                        'secondary' => Payment::STATUS_CANCELLED,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Payment::STATUS_PENDING => 'Pending',
                        Payment::STATUS_COMPLETED => 'Completed',
                        Payment::STATUS_FAILED => 'Failed',
                        Payment::STATUS_CANCELLED => 'Cancelled',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('offer.name')
                    ->label('Offer')
                    ->limit(20)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        Payment::TYPE_DEPOSIT => 'Deposit',
                        Payment::TYPE_AUTO_DEBIT => 'Auto Debit',
                        Payment::TYPE_BONUS => 'Bonus',
                        Payment::TYPE_AD_SPEND => 'Ad Spend',
                        Payment::TYPE_REFUND => 'Refund',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Payment::STATUS_PENDING => 'Pending',
                        Payment::STATUS_COMPLETED => 'Completed',
                        Payment::STATUS_FAILED => 'Failed',
                        Payment::STATUS_CANCELLED => 'Cancelled',
                    ])
                    ->multiple(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Manual Transaction'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No transactions found')
            ->emptyStateDescription('This user has no payment transactions yet.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->paginated([10, 25, 50]);
    }
}
