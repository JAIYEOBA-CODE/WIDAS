<?php

namespace App\Contracts;

interface AuditServiceInterface
{
    public function log(string $event, string $auditableType, int $auditableId, array $oldValues = [], array $newValues = [], array $metadata = []): void;
    public function getAuditTrail(string $auditableType, int $auditableId);
    public function getRecentActivities(int $limit = 50);
    public function getUserActivities(int $userId, int $limit = 50);
}
