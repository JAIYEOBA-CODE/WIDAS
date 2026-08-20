<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\SecurityLog;
use App\Models\User;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        return Inertia::render('User/Dashboard', [
            'stats' => [
                'total_logins' => SecurityLog::where('user_id', $user->id)->where('type', 'login')->count(),
                'failed_attempts' => \App\Models\LoginAttempt::where('user_id', $user->id)->failed()->count(),
                'alerts' => Alert::where('user_id', $user->id)->count(),
                'unread_alerts' => Alert::where('user_id', $user->id)->unread()->count(),
            ],
            'recentActivity' => SecurityLog::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'recentAlerts' => Alert::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ]);
    }

    public function profile()
    {
        $user = auth()->user()->load('role');
        return Inertia::render('User/Profile', ['user' => $user]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function activity()
    {
        $activities = \App\Models\ActivityLog::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return Inertia::render('User/Activity', ['activities' => $activities]);
    }
}
