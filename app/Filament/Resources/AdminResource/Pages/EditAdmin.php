<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle password hashing
        if (isset($data['password']) && filled($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle email verification
        if (isset($data['email_verified'])) {
            if ($data['email_verified'] && $this->record->email_verified_at === null) {
                $data['email_verified_at'] = now();
            } elseif (! $data['email_verified'] && $this->record->email_verified_at !== null) {
                $data['email_verified_at'] = null;
            }
            unset($data['email_verified']);
        }

        return $data;
    }
}
