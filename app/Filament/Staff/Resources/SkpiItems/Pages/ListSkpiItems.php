<?php

namespace App\Filament\Staff\Resources\SkpiItems\Pages;

use App\Filament\Staff\Resources\SkpiItems\SkpiItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkpiItems extends ListRecords
{
    protected static string $resource = SkpiItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
