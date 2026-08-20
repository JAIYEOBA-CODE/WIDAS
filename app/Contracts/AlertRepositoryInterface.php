<?php

namespace App\Contracts;

use App\Models\Alert;
use Illuminate\Pagination\LengthAwarePaginator;

interface AlertRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?Alert;
    public function create(array $data): Alert;
    public function markAsRead(Alert $alert): bool;
    public function markAsResolved(Alert $alert): bool;
    public function getUnreadCount(): int;
    public function getCriticalUnreadCount(): int;
    public function getRecent(int $limit = 10): array;
}
