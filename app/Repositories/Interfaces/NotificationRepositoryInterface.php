<?php

namespace App\Repositories\Interfaces;

use App\Models\Notification;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function find(int $id): ?Notification;

    public function create(array $data): Notification;

    public function update(int $id, array $data): ?Notification;

    public function delete(int $id): bool;

    public function getPendingNotifications(int $limit = 100): Collection;

    public function getFailedNotifications(int $limit = 100): Collection;

    public function getStats(?int $tenantId = null): array;

    public function getUserNotifications(int $userId, int $limit = 50): Collection;

    public function markAsSent(int $id): bool;

    public function markAsFailed(int $id, string $error, array $trace = []): bool;

    public function incrementAttempts(int $id): bool;


    public function getReadyNotifications(int $limit = 100): Collection;
}
