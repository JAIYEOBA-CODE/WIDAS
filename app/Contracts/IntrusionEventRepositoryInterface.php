<?php

namespace App\Contracts;

use App\Models\IntrusionEvent;
use Illuminate\Pagination\LengthAwarePaginator;

interface IntrusionEventRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?IntrusionEvent;
    public function create(array $data): IntrusionEvent;
    public function update(IntrusionEvent $event, array $data): IntrusionEvent;
    public function resolve(IntrusionEvent $event, int $resolvedBy, string $notes = null): bool;
    public function getUnresolved(): array;
    public function getBySeverity(string $severity): array;
    public function getByType(string $type): array;
    public function getStats(): array;
}
