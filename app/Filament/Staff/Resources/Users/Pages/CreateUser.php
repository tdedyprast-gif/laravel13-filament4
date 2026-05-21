<?php

namespace App\Filament\Staff\Resources\Users\Pages;

use App\Filament\Staff\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
