<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationGroup = 'Financial Management';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationLabel = 'All Transactions';
    
    protected static ?string $modelLabel = 'Transaction';
    
    protected static ?string $pluralModelLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                Payment::TYPE_DEPOSIT => 'Deposit',
                                Payment::TYPE_AUTO_DEBIT => 'Auto Debit',
                                Payment::TYPE_BONUS => 'Bonus',
                                Payment::TYPE_AD_SPEND => 'Ad Spend',
                                Payment::TYPE_REFUND => 'Refund',
                            ])
                            ->default(Payment::TYPE_DEPOSIT),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                Payment::STATUS_PENDING => 'Pending',
                                Payment::STATUS_COMPLETED => 'Completed',
                                Payment::STATUS_FAILED => 'Failed',
                                Payment::STATUS_CANCELLED => 'Cancelled',
                            ])
                            ->default(Payment::STATUS_PENDING),
                    ])->columns(2),
                
                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        Forms\Components\TextInput::make('stripe_payment_id')
                            ->label('Stripe Payment ID')
                            ->maxLength(255)
                            ->placeholder('pi_xxxxxxxxxx'),
                        Forms\Components\TextInput::make('stripe_customer_id')
                            ->label('Stripe Customer ID')
                            ->maxLength(255)
                            ->placeholder('cus_xxxxxxxxxx'),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Internal Transaction ID')
                            ->maxLength(255),
                        Forms\Components\Select::make('offer_id')
                            ->relationship('offer', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select offer (if applicable)'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\TextInput::make('bonus_amount')
                            ->label('Bonus Amount')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->default(0.00),
                        Forms\Components\Textarea::make('description')
                            ->placeholder('Payment description or notes')
                            ->rows(3),
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable()
                    ->alignEnd(),
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
                Tables\Columns\TextColumn::make('bonus_amount')
                    ->label('Bonus')
                    ->money('USD')
                    ->alignEnd()
                    ->toggleable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('offer.name')
                    ->label('Offer')
                    ->limit(30)
                    ->toggleable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('stripe_payment_id')
                    ->label('Stripe ID')
                    ->limit(20)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->limit(20)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
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
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('complete')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Payment $record) {
                            if ($record->status === Payment::STATUS_PENDING) {
                                app(\App\Services\DepositService::class)->completeDeposit($record);
                                \Filament\Notifications\Notification::make()
                                    ->title('Payment completed successfully')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Complete Payment')
                        ->modalDescription('This will complete the pending payment and add funds to the user\'s balance.')
                        ->visible(fn (Payment $record): bool => $record->status === Payment::STATUS_PENDING),
                    Tables\Actions\Action::make('refund')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('warning')
                        ->form([
                            Forms\Components\TextInput::make('refund_amount')
                                ->label('Refund Amount')
                                ->numeric()
                                ->prefix('$')
                                ->required()
                                ->default(fn (Payment $record) => $record->amount),
                            Forms\Components\Textarea::make('refund_reason')
                                ->label('Refund Reason')
                                ->required()
                                ->placeholder('Reason for refund...'),
                            Forms\Components\Toggle::make('process_stripe_refund')
                                ->label('Process Stripe Refund')
                                ->default(true)
                                ->helperText('If enabled, will also process refund through Stripe'),
                        ])
                        ->action(function (Payment $record, array $data) {
                            $user = $record->user;
                            $refundAmount = $data['refund_amount'];
                            $refundReason = $data['refund_reason'];
                            $processStripeRefund = $data['process_stripe_refund'] ?? false;
                            
                            \Illuminate\Support\Facades\DB::beginTransaction();
                            
                            try {
                                $stripeRefundId = null;
                                
                                // Process Stripe refund if requested and payment has Stripe ID
                                if ($processStripeRefund && $record->stripe_payment_id) {
                                    try {
                                        // Initialize Stripe
                                        $stripeSecret = config('services.stripe.secret') ?: env('STRIPE_SECRET');
                                        if (empty($stripeSecret)) {
                                            throw new \Exception('Stripe API key not configured');
                                        }
                                        \Stripe\Stripe::setApiKey($stripeSecret);
                                        
                                        // Create Stripe refund
                                        $stripeRefund = \Stripe\Refund::create([
                                            'payment_intent' => $record->stripe_payment_id,
                                            'amount' => intval($refundAmount * 100), // Convert to cents
                                            'reason' => 'requested_by_customer',
                                            'metadata' => [
                                                'refund_reason' => $refundReason,
                                                'admin_initiated' => 'true',
                                                'original_payment_id' => $record->id,
                                            ]
                                        ]);
                                        
                                        $stripeRefundId = $stripeRefund->id;
                                        
                                        \Illuminate\Support\Facades\Log::info('Stripe refund processed', [
                                            'stripe_refund_id' => $stripeRefundId,
                                            'amount' => $refundAmount,
                                            'original_payment_id' => $record->id
                                        ]);
                                        
                                    } catch (\Exception $e) {
                                        \Illuminate\Support\Facades\Log::error('Stripe refund failed', [
                                            'error' => $e->getMessage(),
                                            'payment_id' => $record->id,
                                            'amount' => $refundAmount
                                        ]);
                                        
                                        \Filament\Notifications\Notification::make()
                                            ->title('Stripe refund failed')
                                            ->body("Failed to process Stripe refund: " . $e->getMessage())
                                            ->danger()
                                            ->send();
                                        
                                        \Illuminate\Support\Facades\DB::rollBack();
                                        return;
                                    }
                                }
                                
                                // Create refund payment record
                                $refund = $user->payments()->create([
                                    'amount' => $refundAmount,
                                    'type' => Payment::TYPE_REFUND,
                                    'status' => Payment::STATUS_COMPLETED,
                                    'transaction_id' => $stripeRefundId ?: ('refund_' . uniqid()),
                                    'description' => "Refund: {$refundReason}",
                                    'stripe_payment_id' => $stripeRefundId,
                                    'metadata' => [
                                        'original_payment_id' => $record->id,
                                        'refund_reason' => $refundReason,
                                        'admin_initiated' => true,
                                        'stripe_processed' => $processStripeRefund,
                                        'stripe_refund_id' => $stripeRefundId,
                                    ],
                                ]);
                                
                                // Add refund amount to user balance
                                $user->increment('balance', $refundAmount);
                                
                                \Illuminate\Support\Facades\DB::commit();
                                
                                $message = "Refunded \${$refundAmount} to {$user->name}";
                                if ($stripeRefundId) {
                                    $message .= " (Stripe Refund ID: {$stripeRefundId})";
                                }
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Refund processed successfully')
                                    ->body($message)
                                    ->success()
                                    ->send();
                                    
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\DB::rollBack();
                                
                                \Illuminate\Support\Facades\Log::error('Refund processing failed', [
                                    'error' => $e->getMessage(),
                                    'payment_id' => $record->id,
                                    'amount' => $refundAmount
                                ]);
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Refund processing failed')
                                    ->body("Failed to process refund: " . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Process Refund')
                        ->modalDescription('This will create a refund payment and optionally process the refund through Stripe.')
                        ->visible(fn (Payment $record): bool => 
                            in_array($record->status, [Payment::STATUS_COMPLETED]) && 
                            in_array($record->type, [Payment::TYPE_DEPOSIT, Payment::TYPE_AUTO_DEBIT])
                        ),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No payments found')
            ->emptyStateDescription('Payments will appear here once users make deposits or transactions.')
            ->emptyStateIcon('heroicon-o-credit-card')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
