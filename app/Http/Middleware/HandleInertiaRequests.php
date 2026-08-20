<?php

namespace App\Http\Middleware;

use App\Models\Alert;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role?->slug,
                    'is_admin' => $user->isAdmin(),
                    'is_analyst' => $user->isAnalyst(),
                ] : null,
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'warning' => $request->session()->get('warning'),
            ],
            'unread_alerts_count' => $user ? Alert::unread()->count() : 0,
            'critical_alerts_count' => $user ? Alert::unread()->bySeverity('critical')->count() : 0,
        ]);
    }
}
