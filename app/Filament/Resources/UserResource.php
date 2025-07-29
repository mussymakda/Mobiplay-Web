<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'User Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter full name'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter email address'),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'Agency' => 'Agency',
                                'Advertiser' => 'Advertiser',
                            ])
                            ->default('Advertiser')
                            ->label('User Type'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            ->placeholder('Enter password'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Toggle::make('email_verified')
                            ->label('Email Verified')
                            ->helperText('Toggle to verify/unverify user email')
                            ->afterStateHydrated(function (Forms\Components\Toggle $component, $state, $record) {
                                $component->state($record?->email_verified_at !== null);
                            })
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('stripe_customer_id')
                            ->label('Stripe Customer ID')
                            ->maxLength(255)
                            ->placeholder('Stripe customer identifier'),
                        Forms\Components\FileUpload::make('profile_image')
                            ->label('Profile Image')
                            ->image()
                            ->directory('profile-images')
                            ->disk('public')
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048),
                    ])->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->placeholder('Enter phone number'),
                        Forms\Components\TextInput::make('address_line1')
                            ->label('Address Line 1')
                            ->placeholder('Street address'),
                        Forms\Components\TextInput::make('address_line2')
                            ->label('Address Line 2')
                            ->placeholder('Apartment, suite, etc.'),
                        Forms\Components\TextInput::make('city')
                            ->label('City')
                            ->placeholder('Enter city'),
                        Forms\Components\TextInput::make('state_province')
                            ->label('State/Province')
                            ->placeholder('Enter state or province'),
                        Forms\Components\TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->placeholder('Enter postal/zip code'),
                        Forms\Components\TextInput::make('country')
                            ->label('Country')
                            ->placeholder('Enter country'),
                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Agency' => 'success',
                        'Advertiser' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('balance')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('bonus_balance')
                    ->label('Bonus')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('total_balance')
                    ->label('Total Balance')
                    ->money('USD')
                    ->alignEnd()
                    ->sortable()
                    ->weight('bold')
                    ->color(fn (User $record): string => 
                        $record->total_balance <= 0 ? 'danger' : 
                        ($record->total_balance < 50 ? 'warning' : 'success')
                    ),
                Tables\Columns\IconColumn::make('auto_debit_enabled')
                    ->label('Auto-Debit')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('stripe_customer_id')
                    ->label('Stripe ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Not set')
                    ->limit(15),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'Agency' => 'Agency',
                        'Advertiser' => 'Advertiser',
                    ]),
                Tables\Filters\Filter::make('verified')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at'))
                    ->label('Verified Users'),
                Tables\Filters\Filter::make('unverified')
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at'))
                    ->label('Unverified Users'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentsRelationManager::class,
            RelationManagers\AdsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
