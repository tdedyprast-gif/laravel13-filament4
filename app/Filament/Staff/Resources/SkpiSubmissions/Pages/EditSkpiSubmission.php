<?php

namespace App\Filament\Staff\Resources\SkpiSubmissions\Pages;

use App\Filament\Staff\Resources\SkpiSubmissions\SkpiSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditSkpiSubmission extends EditRecord
{
    protected static string $resource = SkpiSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => ! Auth::user()?->hasRole('mahasiswa')),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        if ($user?->hasRole('mahasiswa')) {
            unset(
                $data['user_id'],
                $data['msmhs_id'],
                $data['status'],
                $data['verified_at'],
                $data['verified_by'],
                $data['rejected_at'],
                $data['rejected_by'],
                $data['rejection_note'],
            );
        }

        return $data;
    }
}
