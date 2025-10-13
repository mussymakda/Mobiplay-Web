<?php

namespace App\Filament\Resources\DriverLocationLogResource\Pages;

use App\Filament\Resources\DriverLocationLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDriverLocationLogs extends ListRecords
{
    protected static string $resource = DriverLocationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
