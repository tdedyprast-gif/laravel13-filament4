<?php

namespace App\Filament\Staff\Resources\SkpiItems\Pages;

use App\Filament\Staff\Resources\SkpiItems\SkpiItemResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSkpiItem extends CreateRecord
{
    protected static string $resource = SkpiItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()?->hasRole('mahasiswa')) {
            $data['is_verified'] = false;
            $data['verified_at'] = null;
            $data['verified_by'] = null;
            $data['verification_note'] = null;
        }

        return $data;
    }
}
