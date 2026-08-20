<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\LoginAttempt;
use App\Models\Role;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(RegisterRequest $request)
    {
        $role = Role::where('slug', 'user')->first();

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $role?->id,
            'is_active' => true,
        ]);

        Auth::login($user);

        SecurityLog::create([
            'user_id' => $user->id,
            'type' => 'registration',
            'severity' => 'info',
            'source_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'message' => "New account registered for {$user->email} from {$request->ip()}",
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Account created successfully.');
    }

    public function login(LoginRequest $request)
    {
        $key = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            LoginAttempt::create([
                'email' => $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'was_successful' => false,
                'failure_reason' => 'rate_limited',
            ]);

            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            if ($user->isLocked()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is temporarily locked due to multiple failed login attempts.',
                ]);
            }

            RateLimiter::clear($key);

            $user->update(['login_attempts' => 0, 'locked_until' => null]);

            LoginAttempt::create([
                'user_id' => $user->id,
                'email' => $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'was_successful' => true,
            ]);

            SecurityLog::create([
                'user_id' => $user->id,
                'type' => 'login',
                'severity' => 'info',
                'source_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'message' => "Successful authentication for {$user->email} from {$request->ip()}",
            ]);

            $request->session()->regenerate();

            return redirect()->intended($this->getDashboardRoute($user));
        }

        RateLimiter::hit($key);

        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            $user->increment('login_attempts');

            if ($user->login_attempts >= 5) {
                $user->update(['locked_until' => now()->addMinutes(30)]);
                SecurityLog::create([
                    'user_id' => $user->id,
                    'type' => 'account_locked',
                    'severity' => 'warning',
                    'source_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'message' => "Account {$user->email} temporarily locked after exceeding maximum failed login attempts from {$request->ip()}",
                ]);
            }
        }

        LoginAttempt::create([
            'email' => $request->input('email'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'was_successful' => false,
            'failure_reason' => 'invalid_credentials',
        ]);

        SecurityLog::create([
            'type' => 'failed_login',
            'severity' => 'warning',
            'source_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'message' => "Unsuccessful login attempt for {$request->input('email')} originating from {$request->ip()}",
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        SecurityLog::create([
            'user_id' => $user?->id,
            'type' => 'logout',
            'severity' => 'info',
            'source_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'message' => "Session terminated for {$user?->email} from {$request->ip()}",
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function getDashboardRoute($user): string
    {
        return match ($user->role?->slug) {
            'admin' => route('admin.dashboard'),
            'analyst' => route('analyst.dashboard'),
            default => route('user.dashboard'),
        };
    }
}
