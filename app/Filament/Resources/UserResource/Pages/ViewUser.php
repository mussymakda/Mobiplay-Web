<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Ad;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Balance Overview
                Infolists\Components\Section::make('Balance Overview')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('balance')
                                    ->label('Main Balance')
                                    ->formatStateUsing(fn ($state) => '$'.number_format($state, 2)),
                                Infolists\Components\TextEntry::make('bonus_balance')
                                    ->label('Bonus Balance')
                                    ->formatStateUsing(fn ($state) => '$'.number_format($state, 2)),
                                Infolists\Components\TextEntry::make('total_balance')
                                    ->label('Total Balance')
                                    ->formatStateUsing(fn ($state) => '$'.number_format($state, 2))
                                    ->color('success'),
                            ]),
                    ])
                    ->collapsible(),

                // Spending & Performance Overview
                Infolists\Components\Section::make('Ad Performance')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_spent')
                                    ->label('Total Ad Spend')
                                    ->getStateUsing(function ($record) {
                                        try {
                                            $spent = $record->payments()
                                                ->where('type', 'ad_spend')
                                                ->where('status', 'completed')
                                                ->sum('amount');

                                            return '$'.number_format(abs($spent), 2);
                                        } catch (\Exception $e) {
                                            return '$0.00';
                                        }
                                    })
                                    ->color('danger'),
                                Infolists\Components\TextEntry::make('active_campaigns')
                                    ->label('Active Campaigns')
                                    ->getStateUsing(function ($record) {
                                        try {
                                            return $record->ads()->where('status', Ad::STATUS_ACTIVE)->count();
                                        } catch (\Exception $e) {
                                            return 0;
                                        }
                                    }),
                                Infolists\Components\TextEntry::make('total_impressions')
                                    ->label('Total Impressions')
                                    ->getStateUsing(function ($record) {
                                        try {
                                            return number_format($record->ads()->sum('impressions'));
                                        } catch (\Exception $e) {
                                            return '0';
                                        }
                                    })
                                    ->color('success'),
                            ]),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('User Information')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Full Name'),
                                Infolists\Components\TextEntry::make('email')
                                    ->label('Email Address'),
                                Infolists\Components\TextEntry::make('type')
                                    ->label('User Type')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('email_verified_at')
                                    ->label('Email Verified')
                                    ->getStateUsing(fn ($record) => $record->email_verified_at ? 'Yes' : 'No')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Member Since')
                                    ->dateTime(),
                                Infolists\Components\TextEntry::make('stripe_customer_id')
                                    ->label('Stripe Customer ID')
                                    ->placeholder('Not set'),
                            ]),
                    ]),

                // Auto-Debit Settings
                Infolists\Components\Section::make('Auto-Debit Settings')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('auto_debit_enabled')
                                    ->label('Auto-Debit Enabled')
                                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('auto_debit_threshold')
                                    ->label('Auto-Debit Threshold')
                                    ->formatStateUsing(fn ($state) => $state ? '$'.number_format($state, 2) : 'Not set'),
                            ]),
                    ]),
            ]);
    }
}
