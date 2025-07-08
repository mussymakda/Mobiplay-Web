<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filament Panel
    |--------------------------------------------------------------------------
    |
    | This value is the name of the default Filament panel. When building
    | URLs, Filament will use this value to determine which panel to use
    | when generating URLs.
    |
    */

    'default' => env('FILAMENT_PANEL', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Panels
    |--------------------------------------------------------------------------
    |
    | This array contains configuration for each of your Filament panels.
    | You can add multiple panels to your application, each with its own
    | configuration.
    |
    */

    'panels' => [

        'admin' => [
            'id' => 'admin',
            'path' => '/admin',
            'login' => \Filament\Http\Livewire\Auth\Login::class,
            'auth' => [
                'guard' => 'web',
                'pages' => [
                    'login' => \Filament\Http\Livewire\Auth\Login::class,
                ],
            ],
            'pages' => [
                'dashboard' => \Filament\Pages\Dashboard::class,
            ],
            'resources' => [
                // Add your resources here
            ],
            'widgets' => [
                // Add your widgets here
            ],
        ],

    ],

];
