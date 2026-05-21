<?php

namespace App\Filament\Staff\Resources\Users\Pages;

use App\Filament\Staff\Resources\Users\UserResource;
use App\Models\Msmhs;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (filled($data['msmhs_id'] ?? null)) {
            $data['name'] = Msmhs::query()->find($data['msmhs_id'])?->nama_mahasiswa ?? ($data['name'] ?? '');
        }

        return $data;
    }
}
