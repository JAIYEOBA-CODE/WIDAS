<?php

namespace App\Listeners;

use App\Events\AlertGenerated;
use App\Models\User;
use App\Notifications\SecurityAlert;

class SendAlertNotification
{
    public function handle(AlertGenerated $event): void
    {
        $users = User::whereHas('role', function ($q) {
            $q->whereIn('slug', ['admin', 'analyst']);
        })->get();

        foreach ($users as $user) {
            $user->notify(new SecurityAlert($event->alert));
        }
    }
}
