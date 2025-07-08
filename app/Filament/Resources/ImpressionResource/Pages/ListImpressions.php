<?php

namespace App\Filament\Resources\ImpressionResource\Pages;

use App\Filament\Resources\ImpressionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImpressions extends ListRecords
{
    protected static string $resource = ImpressionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
