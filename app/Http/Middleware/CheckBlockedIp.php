<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Schema::hasTable('blocked_ips')) {
            return $next($request);
        }

        $ip = $request->ip();

        $blocked = BlockedIp::where('ip_address', $ip)->active()->first();

        if ($blocked) {
            if ($blocked->isExpired()) {
                $blocked->delete();
                return $next($request);
            }

            return Inertia::render('Errors/Blocked', [
                'reason' => $blocked->reason ?? 'Security violation detected',
                'blockedAt' => $blocked->blocked_at?->toIso8601String() ?? now()->toIso8601String(),
                'expiresAt' => $blocked->expires_at?->toIso8601String(),
            ])->toResponse($request);
        }

        return $next($request);
    }
}
