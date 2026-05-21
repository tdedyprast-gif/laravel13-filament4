<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Msmhs;
use Illuminate\Auth\Access\HandlesAuthorization;

class MsmhsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Msmhs');
    }

    public function view(AuthUser $authUser, Msmhs $msmhs): bool
    {
        return $authUser->can('View:Msmhs');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Msmhs');
    }

    public function update(AuthUser $authUser, Msmhs $msmhs): bool
    {
        return $authUser->can('Update:Msmhs');
    }

    public function delete(AuthUser $authUser, Msmhs $msmhs): bool
    {
        return $authUser->can('Delete:Msmhs');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Msmhs');
    }

    public function restore(AuthUser $authUser, Msmhs $msmhs): bool
    {
        return $authUser->can('Restore:Msmhs');
    }

    public function forceDelete(AuthUser $authUser, Msmhs $msmhs): bool
    {
        return $authUser->can('ForceDelete:Msmhs');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Msmhs');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Msmhs');
    }

    public function replicate(AuthUser $authUser, Msmhs $msmhs): bool
    {
        return $authUser->can('Replicate:Msmhs');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Msmhs');
    }

}