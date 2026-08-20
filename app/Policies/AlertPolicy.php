<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;

class AlertPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-alerts');
    }

    public function view(User $user, Alert $alert): bool
    {
        return $user->hasPermission('view-alerts');
    }

    public function update(User $user, Alert $alert): bool
    {
        return $user->hasPermission('manage-alerts');
    }

    public function acknowledge(User $user, Alert $alert): bool
    {
        return $user->hasPermission('acknowledge-alerts');
    }

    public function delete(User $user, Alert $alert): bool
    {
        return $user->hasPermission('manage-alerts');
    }
}
