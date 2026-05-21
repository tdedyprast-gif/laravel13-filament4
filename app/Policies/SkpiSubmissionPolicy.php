<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SkpiSubmission;
use Illuminate\Auth\Access\HandlesAuthorization;

class SkpiSubmissionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        if ($authUser->hasRole('mahasiswa')) {
            return true;
        }

        return $authUser->can('ViewAny:SkpiSubmission');
    }

    public function view(AuthUser $authUser, SkpiSubmission $skpiSubmission): bool
    {
        if ($authUser->hasRole('mahasiswa')) {
            return $skpiSubmission->user_id === $authUser->id;
        }

        return $authUser->can('View:SkpiSubmission');
    }

    public function create(AuthUser $authUser): bool
    {
        if ($authUser->hasRole('mahasiswa')) {
            return filled($authUser->msmhs_id) && $authUser->activation_status === 'active';
        }

        return $authUser->can('Create:SkpiSubmission');
    }

    public function update(AuthUser $authUser, SkpiSubmission $skpiSubmission): bool
    {
        if ($authUser->hasRole('mahasiswa')) {
            return $skpiSubmission->user_id === $authUser->id
                && in_array($skpiSubmission->status, ['submitted', 'rejected'], true);
        }

        return $authUser->can('Update:SkpiSubmission');
    }

    public function delete(AuthUser $authUser, SkpiSubmission $skpiSubmission): bool
    {
        if ($authUser->hasRole('mahasiswa')) {
            return false;
        }

        return $authUser->can('Delete:SkpiSubmission');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SkpiSubmission');
    }

    public function restore(AuthUser $authUser, SkpiSubmission $skpiSubmission): bool
    {
        return $authUser->can('Restore:SkpiSubmission');
    }

    public function forceDelete(AuthUser $authUser, SkpiSubmission $skpiSubmission): bool
    {
        return $authUser->can('ForceDelete:SkpiSubmission');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SkpiSubmission');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SkpiSubmission');
    }

    public function replicate(AuthUser $authUser, SkpiSubmission $skpiSubmission): bool
    {
        return $authUser->can('Replicate:SkpiSubmission');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SkpiSubmission');
    }

}
