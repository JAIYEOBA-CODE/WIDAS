<?php

namespace App\Policies;

use App\Models\IntrusionEvent;
use App\Models\User;

class IntrusionEventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-threats');
    }

    public function view(User $user, IntrusionEvent $event): bool
    {
        return $user->hasPermission('view-threats');
    }

    public function resolve(User $user, IntrusionEvent $event): bool
    {
        return $user->hasPermission('manage-threats');
    }
}
