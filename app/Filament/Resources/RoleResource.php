<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Admin Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Role Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->rules(['alpha_dash'])
                            ->helperText('Use lowercase letters, numbers, and underscores only (e.g., content_manager)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('display_name', str($state)->title()->replace('_', ' '));
                                }
                            }),

                        Forms\Components\TextInput::make('display_name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Human-readable name (e.g., Content Manager)'),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(500)
                            ->helperText('Brief description of this role\'s purpose'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Inactive roles cannot be assigned to admins'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Permissions')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->relationship('permissions', 'display_name')
                            ->options(function () {
                                return Permission::query()
                                    ->orderBy('category')
                                    ->orderBy('display_name')
                                    ->get()
                                    ->pluck('display_name', 'id')
                                    ->toArray();
                            })
                            ->columns(3)
                            ->gridDirection('row')
                            ->helperText('Select the permissions this role should have')
                            ->searchable()
                            ->bulkToggleable(),
                    ])
                    ->collapsible()
                    ->persistCollapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Role Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('System Name')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('description')
                    ->wrap()
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('admins_count')
                    ->label('Assigned Admins')
                    ->counts('admins')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
