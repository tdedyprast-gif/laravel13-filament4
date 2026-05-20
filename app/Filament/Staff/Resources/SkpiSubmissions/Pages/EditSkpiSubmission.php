<?php

namespace App\Filament\Staff\Resources\SkpiSubmissions\Pages;

use App\Filament\Staff\Resources\SkpiSubmissions\SkpiSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSkpiSubmission extends EditRecord
{
    protected static string $resource = SkpiSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
