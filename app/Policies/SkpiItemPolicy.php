<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SkpiItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class SkpiItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SkpiItem');
    }

    public function view(AuthUser $authUser, SkpiItem $skpiItem): bool
    {
        return $authUser->can('View:SkpiItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SkpiItem');
    }

    public function update(AuthUser $authUser, SkpiItem $skpiItem): bool
    {
        return $authUser->can('Update:SkpiItem');
    }

    public function delete(AuthUser $authUser, SkpiItem $skpiItem): bool
    {
        return $authUser->can('Delete:SkpiItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SkpiItem');
    }

    public function restore(AuthUser $authUser, SkpiItem $skpiItem): bool
    {
        return $authUser->can('Restore:SkpiItem');
    }

    public function forceDelete(AuthUser $authUser, SkpiItem $skpiItem): bool
    {
        return $authUser->can('ForceDelete:SkpiItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SkpiItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SkpiItem');
    }

    public function replicate(AuthUser $authUser, SkpiItem $skpiItem): bool
    {
        return $authUser->can('Replicate:SkpiItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SkpiItem');
    }

}