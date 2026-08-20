<?php

namespace App\Repositories;

use App\Contracts\IntrusionEventRepositoryInterface;
use App\Models\IntrusionEvent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class IntrusionEventRepository implements IntrusionEventRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = IntrusionEvent::with(['user', 'threatRule']);

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (!empty($filters['severity'])) {
            $query->bySeverity($filters['severity']);
        }

        if (!empty($filters['is_resolved'])) {
            $query->where('is_resolved', $filters['is_resolved'] === 'true' || $filters['is_resolved'] === true);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('source_ip', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        $perPage = $filters['per_page'] ?? 15;
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?IntrusionEvent
    {
        return IntrusionEvent::with(['user', 'threatRule', 'resolver'])->find($id);
    }

    public function create(array $data): IntrusionEvent
    {
        return IntrusionEvent::create($data);
    }

    public function update(IntrusionEvent $event, array $data): IntrusionEvent
    {
        $event->update($data);
        return $event->fresh();
    }

    public function resolve(IntrusionEvent $event, int $resolvedBy, string $notes = null): bool
    {
        return $event->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $resolvedBy,
            'resolution_notes' => $notes,
        ]);
    }

    public function getUnresolved(): array
    {
        return IntrusionEvent::unresolved()
            ->with(['user', 'threatRule'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->toArray();
    }

    public function getBySeverity(string $severity): array
    {
        return IntrusionEvent::bySeverity($severity)
            ->with(['user', 'threatRule'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->toArray();
    }

    public function getByType(string $type): array
    {
        return IntrusionEvent::byType($type)
            ->with(['user', 'threatRule'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        return [
            'total' => IntrusionEvent::count(),
            'unresolved' => IntrusionEvent::unresolved()->count(),
            'critical' => IntrusionEvent::bySeverity('critical')->count(),
            'high' => IntrusionEvent::bySeverity('high')->count(),
            'medium' => IntrusionEvent::bySeverity('medium')->count(),
            'low' => IntrusionEvent::bySeverity('low')->count(),
        ];
    }
}
