<?php

namespace App\Repositories;

use App\Contracts\AlertRepositoryInterface;
use App\Models\Alert;
use Illuminate\Pagination\LengthAwarePaginator;

class AlertRepository implements AlertRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Alert::with(['user', 'intrusionEvent']);

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['severity'])) {
            $query->bySeverity($filters['severity']);
        }

        if (!empty($filters['is_read'])) {
            $query->where('is_read', $filters['is_read'] === 'true' || $filters['is_read'] === true);
        }

        if (!empty($filters['is_resolved'])) {
            $query->where('is_resolved', $filters['is_resolved'] === 'true' || $filters['is_resolved'] === true);
        }

        $perPage = $filters['per_page'] ?? 15;
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?Alert
    {
        return Alert::with(['user', 'intrusionEvent'])->find($id);
    }

    public function create(array $data): Alert
    {
        return Alert::create($data);
    }

    public function markAsRead(Alert $alert): bool
    {
        return $alert->update(['is_read' => true, 'read_at' => now()]);
    }

    public function markAsResolved(Alert $alert): bool
    {
        return $alert->update(['is_resolved' => true, 'resolved_at' => now()]);
    }

    public function getUnreadCount(): int
    {
        return Alert::unread()->count();
    }

    public function getCriticalUnreadCount(): int
    {
        return Alert::unread()->bySeverity('critical')->count();
    }

    public function getRecent(int $limit = 10): array
    {
        return Alert::with(['user', 'intrusionEvent'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
