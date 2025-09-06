<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function update(User $user, Role $role): bool
    {
        // Role "Super Admin" tidak bisa diubah sama sekali
        if ($role->name === 'Super Admin') {
            return false;
        }

        // Role "Admin" hanya bisa diubah oleh Super Admin
        if ($role->name === 'Admin') {
            return $user->hasRole('Super Admin');
        }

        return true;
    }

    public function delete(User $user, Role $role): bool
    {
        $defaultRoles = Role::defaultRoles();

        // Role default tidak bisa dihapus
        if (in_array($role->name, $defaultRoles, true)) {
            return false;
        }

        return true;
    }
}
