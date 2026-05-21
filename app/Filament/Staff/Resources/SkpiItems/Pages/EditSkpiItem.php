<?php

namespace App\Filament\Staff\Resources\SkpiItems\Pages;

use App\Filament\Staff\Resources\SkpiItems\SkpiItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditSkpiItem extends EditRecord
{
    protected static string $resource = SkpiItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Auth::user()?->hasRole('mahasiswa')) {
            unset(
                $data['is_verified'],
                $data['verified_at'],
                $data['verified_by'],
                $data['verification_note'],
            );
        }

        return $data;
    }
}
