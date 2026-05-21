<?php

namespace App\Filament\Staff\Resources\SkpiSubmissions\Pages;

use App\Filament\Staff\Resources\SkpiSubmissions\SkpiSubmissionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSkpiSubmission extends CreateRecord
{
    protected static string $resource = SkpiSubmissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        if ($user?->hasRole('mahasiswa')) {
            $data['user_id'] = $user->id;
            $data['msmhs_id'] = $user->msmhs_id;
            $data['status'] = 'submitted';
            $data['submitted_at'] = now();
        }

        return $data;
    }
}
