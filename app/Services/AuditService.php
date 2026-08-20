<?php

namespace App\Services;

use App\Contracts\AuditServiceInterface;
use App\Models\ActivityLog;
use App\Models\AuditLog;

class AuditService implements AuditServiceInterface
{
    public function log(string $event, string $auditableType, int $auditableId, array $oldValues = [], array $newValues = [], array $metadata = []): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getAuditTrail(string $auditableType, int $auditableId)
    {
        return AuditLog::where('auditable_type', $auditableType)
            ->where('auditable_id', $auditableId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRecentActivities(int $limit = 50)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUserActivities(int $userId, int $limit = 50)
    {
        return ActivityLog::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
