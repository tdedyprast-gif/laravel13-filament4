<?php

namespace App\Filament\Staff\Resources\SkpiSubmissions\Pages;

use App\Filament\Staff\Resources\SkpiSubmissions\SkpiSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkpiSubmissions extends ListRecords
{
    protected static string $resource = SkpiSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
