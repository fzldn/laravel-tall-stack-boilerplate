<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ROLES_VIEWANY->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can(Permission::ROLES_VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::ROLES_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        if ($role->isSuperAdmin()) {
            return false;
        }

        return $user->can(Permission::ROLES_UPDATE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if ($role->isSuperAdmin()) {
            return false;
        }

        return $user->can(Permission::ROLES_DELETE->value);
    }

    /**
     * Determine whether the user can delete models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can(Permission::ROLES_DELETEANY->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return true;
    }

    public function attach(User $user): bool
    {
        return $user->can(Permission::ROLES_ATTACH->value);
    }

    public function detach(User $user, Role $role): bool
    {
        if ($role->isSuperAdmin()) {
            return false;
        }

        return $user->can(Permission::ROLES_DETACH->value);
    }

    public function detachAny(User $user): bool
    {
        return $user->can(Permission::ROLES_DETACHANY->value);
    }
}
