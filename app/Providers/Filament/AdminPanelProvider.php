<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('admin')
            ->colors([
                'primary' => [
                    50 => '255, 255, 255',   // white (light mode bg, dark mode text)
                    100 => '245, 245, 245',  // light gray
                    200 => '230, 230, 230',
                    300 => '180, 180, 180',
                    400 => '120, 120, 120',
                    500 => '60, 60, 60',     // neutral dark gray
                    600 => '40, 40, 40',     // near-black (light mode text)
                    700 => '20, 20, 20',
                    800 => '240, 240, 240',  // light tone for dark mode text/icons
                    900 => '255, 255, 255',  // pure white (used in dark mode)
                    950 => '255, 255, 255',  // pure white (dark mode emphasis)
                ],

                // Reuse slate for secondary colors or customize further
                'secondary' => [
                    50 => '250, 250, 250',
                    100 => '240, 240, 240',
                    200 => '220, 220, 220',
                    300 => '180, 180, 180',
                    400 => '140, 140, 140',
                    500 => '100, 100, 100',
                    600 => '80, 80, 80',
                    700 => '60, 60, 60',
                    800 => '40, 40, 40',
                    900 => '20, 20, 20',
                    950 => '10, 10, 10',
                ],

                // Status colors (optional: grayscale for minimalism, or subtle color accents)
                'success' => [
                    500 => '34, 197, 94',   // Light green
                    600 => '22, 163, 74',
                ],
                'info' => [
                    500 => '59, 130, 246',  // Light blue
                    600 => '37, 99, 235',
                ],
                'warning' => [
                    500 => '234, 179, 8',   // Yellow
                    600 => '202, 138, 4',
                ],
                'danger' => [
                    500 => '239, 68, 68',   // Red
                    600 => '220, 38, 38',
                ],

                // Neutral grays for generic elements
                'gray' => [
                    50 => '250, 250, 250',
                    100 => '240, 240, 240',
                    200 => '220, 220, 220',
                    300 => '180, 180, 180',
                    400 => '140, 140, 140',
                    500 => '100, 100, 100',
                    600 => '80, 80, 80',
                    700 => '60, 60, 60',
                    800 => '40, 40, 40',
                    900 => '20, 20, 20',
                    950 => '10, 10, 10',
                ],
            ])

            ->brandName('Mobiplay Admin')
            ->brandLogo(asset('assets/images/logo.png'))
            ->brandLogoHeight('3rem')
            ->favicon('/assets/images/logo.png')
            ->darkMode()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                \App\Filament\Widgets\KeyStatsWidget::class,
                \App\Filament\Widgets\PerformanceTrendsWidget::class,
                \App\Filament\Widgets\ImpressionsWidgetNew::class,
                \App\Filament\Widgets\QrImpressionsWidgetNew::class,
            ])
            // Prevent auto-discovery of widgets to avoid loading deprecated ones
            ->discoverWidgets(in: '', for: '')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
