<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationGroup = 'Ad Management';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationLabel = 'Packages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter package name')
                            ->helperText('A descriptive name for this ad package'),
                        Forms\Components\TextInput::make('priority_level')
                            ->required()
                            ->numeric()
                            ->placeholder('Enter priority level (e.g., 1, 2, 3)')
                            ->helperText('Higher numbers = higher priority')
                            ->minValue(1)
                            ->maxValue(100),
                        Forms\Components\TextInput::make('priority_text')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter priority display text (e.g., High, Mid, Low)')
                            ->helperText('Text to display to users for this priority level'),
                        Forms\Components\CheckboxList::make('ad_showing_conditions')
                            ->label('Ad Showing Conditions')
                            ->options([
                                'rush_hours' => 'Rush Hours',
                                'normal_hours' => 'Normal Hours',
                                'holidays' => 'Holidays',
                            ])
                            ->helperText('Select when ads from this package can be shown')
                            ->required()
                            ->columns(3),
                        Forms\Components\Textarea::make('description')
                            ->placeholder('Enter package description')
                            ->helperText('Brief description of what this package offers')
                            ->rows(3),
                    ])->columns(2),
                
                Forms\Components\Section::make('Pricing & Status')
                    ->schema([
                        Forms\Components\TextInput::make('cost_per_impression')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.0001)
                            ->placeholder('0.0000')
                            ->helperText('Cost per impression in USD'),
                        Forms\Components\TextInput::make('cost_per_qr_scan')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.0001)
                            ->placeholder('0.0000')
                            ->helperText('Cost per QR scan in USD'),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->helperText('Whether this package is available for selection'),
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
                    ->weight('medium')
                    ->wrap(),
                Tables\Columns\TextColumn::make('priority_level')
                    ->label('Priority #')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 80 => 'danger',
                        $state >= 60 => 'warning',
                        $state >= 40 => 'success',
                        default => 'primary',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority_text')
                    ->label('Priority Text')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'high' => 'danger',
                        'medium', 'mid' => 'warning',
                        'low' => 'success',
                        default => 'primary',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('formatted_conditions')
                    ->label('Conditions')
                    ->getStateUsing(fn (Package $record): string => $record->formatted_conditions)
                    ->badge()
                    ->separator(',')
                    ->color('info')
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cost_per_impression')
                    ->label('Cost/Impression')
                    ->money('USD', divideBy: 1)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_per_qr_scan')
                    ->label('Cost/QR Scan')
                    ->money('USD', divideBy: 1)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Active Packages'),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false))
                    ->label('Inactive Packages'),
                Tables\Filters\Filter::make('high_priority')
                    ->query(fn (Builder $query): Builder => $query->where('priority_level', '>=', 70))
                    ->label('High Priority (70+)'),
                Tables\Filters\SelectFilter::make('priority_text')
                    ->options(function () {
                        return Package::distinct('priority_text')
                            ->whereNotNull('priority_text')
                            ->pluck('priority_text', 'priority_text')
                            ->toArray();
                    })
                    ->label('Priority Text'),
                Tables\Filters\Filter::make('rush_hours')
                    ->query(fn (Builder $query): Builder => $query->whereJsonContains('ad_showing_conditions', 'rush_hours'))
                    ->label('Rush Hours Available'),
                Tables\Filters\Filter::make('normal_hours')
                    ->query(fn (Builder $query): Builder => $query->whereJsonContains('ad_showing_conditions', 'normal_hours'))
                    ->label('Normal Hours Available'),
                Tables\Filters\Filter::make('holidays')
                    ->query(fn (Builder $query): Builder => $query->whereJsonContains('ad_showing_conditions', 'holidays'))
                    ->label('Holidays Available'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('toggle_status')
                        ->label(fn (Package $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (Package $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (Package $record): string => $record->is_active ? 'danger' : 'success')
                        ->action(function (Package $record): void {
                            $record->update(['is_active' => !$record->is_active]);
                        })
                        ->requiresConfirmation()
                        ->modalDescription(fn (Package $record): string => 
                            $record->is_active 
                                ? 'This will deactivate the package and prevent users from selecting it.'
                                : 'This will activate the package and make it available for users to select.'
                        ),
                ])->label('Actions')
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
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => true]);
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => false]);
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('priority_level', 'desc')
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
