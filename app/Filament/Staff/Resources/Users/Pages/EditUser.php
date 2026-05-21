<?php

namespace App\Filament\Staff\Resources\Users\Pages;

use App\Filament\Staff\Resources\Users\UserResource;
use App\Models\Msmhs;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (filled($data['msmhs_id'] ?? null)) {
            $data['name'] = Msmhs::query()->find($data['msmhs_id'])?->nama_mahasiswa ?? ($data['name'] ?? '');
        }

        return $data;
    }
}
