<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $module = $this->getModuleFromRoute($request);
            $action = $this->getActionFromMethod($request);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $action,
                'module' => $module,
                'description' => $this->getDescription($action, $module, $request),
                'old_values' => $request->method() === 'PUT' || $request->method() === 'PATCH' ? $request->all() : [],
                'new_values' => $request->method() === 'POST' ? $request->all() : [],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }

    private function getModuleFromRoute(Request $request): string
    {
        $path = $request->path();
        $segments = explode('/', $path);
        return $segments[0] ?? 'general';
    }

    private function getActionFromMethod(Request $request): string
    {
        return match ($request->method()) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'unknown',
        };
    }

    private function getDescription(string $action, string $module, Request $request): string
    {
        $user = $request->user()?->email ?? 'anonymous';
        $resource = $request->route('user') ?? $request->route('alert') ?? $request->route('blockedIp') ?? $request->route('intrusionEvent') ?? $request->route('setting') ?? null;
        $resourceId = $resource instanceof \Illuminate\Database\Eloquent\Model ? $resource->id : (is_numeric($resource) ? $resource : null);

        return match ($action) {
            'create' => ($resourceId ? "Created {$module} record #{$resourceId}" : "Created new {$module} entry") . " by {$user}",
            'update' => ($resourceId ? "Modified {$module} record #{$resourceId}" : "Updated {$module} entry") . " by {$user}",
            'delete' => ($resourceId ? "Deleted {$module} record #{$resourceId}" : "Deleted {$module} entry") . " by {$user}",
            default => "{$action} operation performed on {$module} by {$user}",
        };
    }
}
