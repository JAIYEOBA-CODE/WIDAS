<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

trait HasAuditTrail
{
    public static function bootHasAuditTrail(): void
    {
        static::created(function ($model) {
            static::logAudit('created', $model, [], $model->toArray());
        });

        static::updated(function ($model) {
            $changed = $model->getChanges();
            $original = array_intersect_key($model->getOriginal(), $changed);
            static::logAudit('updated', $model, $original, $changed);
        });

        static::deleted(function ($model) {
            static::logAudit('deleted', $model, $model->toArray(), []);
        });
    }

    protected static function logAudit(string $event, $model, array $oldValues, array $newValues): void
    {
        $user = auth()->user();

        AuditLog::create([
            'user_id' => $user?->id,
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
