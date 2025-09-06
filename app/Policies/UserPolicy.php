<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $authUser, User $targetUser): bool
    {
        return $this->canManage($authUser, $targetUser);
    }

    public function resetPassword(User $authUser, User $targetUser): bool
    {
        if ($authUser->id === $targetUser->id) {
            return $authUser->hasAnyRole(['Super Admin', 'Admin']);
        }

        return $this->canManage($authUser, $targetUser);
    }

    public function delete(User $authUser, User $targetUser): bool
    {
        if ($authUser->id === $targetUser->id) {
            return false;
        }

        return $this->canManage($authUser, $targetUser);
    }

    private function canManage(User $authUser, User $targetUser): bool
    {
        if ($targetUser->hasRole('Super Admin')) {
            return $authUser->hasRole('Super Admin');
        }

        if ($targetUser->hasRole('Admin')) {
            return $authUser->hasAnyRole(['Super Admin', 'Admin']);
        }

        return $authUser->hasAnyRole(['Super Admin', 'Admin']);
    }
}
