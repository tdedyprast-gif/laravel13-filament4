<?php

namespace App\Filament\Staff\Resources\SkpiItems\Pages;

use App\Filament\Staff\Resources\SkpiItems\SkpiItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSkpiItem extends EditRecord
{
    protected static string $resource = SkpiItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
