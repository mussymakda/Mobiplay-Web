<?php

namespace App\Filament\Resources\ImpressionResource\Pages;

use App\Filament\Resources\ImpressionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImpression extends EditRecord
{
    protected static string $resource = ImpressionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
