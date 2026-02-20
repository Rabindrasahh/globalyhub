<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Enums\NotificationStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessNotificationJob;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function find(int $id): ?Notification
    {
        return Notification::with(['logs'])->find($id);
    }


    public function create(array $data): Notification
    {
        $notification = Notification::create($data);
        ProcessNotificationJob::dispatch($notification->id);
        return $notification;
    }

    public function update(int $id, array $data): ?Notification
    {
        $notification = $this->find($id);

        if (!$notification) {
            return null;
        }

        $notification->update($data);
        return $notification->fresh();
    }

    public function delete(int $id): bool
    {
        $notification = $this->find($id);

        if (!$notification) {
            return false;
        }

        return $notification->delete();
    }


    public function getPendingNotifications(int $limit = 100): Collection
    {
        return Notification::pending()
            ->where(function ($query) {
                $query->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', now());
            })
            ->orderBy('created_at')
            ->limit($limit)
            ->get();
    }

    public function getFailedNotifications(int $limit = 100): Collection
    {
        return Notification::failed()
            ->where('attempts', '<', DB::raw('max_attempts'))
            ->orderBy('updated_at')
            ->limit($limit)
            ->get();
    }

    public function getStats(?int $tenantId = null): array
    {
        $query = Notification::query();

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $total = $query->count();
        $sent = (clone $query)->where('status', NotificationStatus::SENT->value)->count();
        $failed = (clone $query)->where('status', NotificationStatus::FAILED->value)->count();
        $pending = (clone $query)->where('status', NotificationStatus::PENDING->value)->count();
        $processing = (clone $query)->where('status', NotificationStatus::PROCESSING->value)->count();

        return [
            'total' => $total,
            'sent' => $sent,
            'failed' => $failed,
            'pending' => $pending,
            'processing' => $processing,
            'success_rate' => $total > 0 ? round(($sent / $total) * 100, 2) : 0
        ];
    }

    public function getUserNotifications(int $userId, int $limit = 50): Collection
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function markAsSent(int $id): bool
    {
        return $this->update($id, [
            'status' => NotificationStatus::SENT->value,
            'sent_at' => now(),
            'error_message' => null,
            'error_trace' => null
        ]) !== null;
    }

    public function markAsFailed(int $id, string $error, array $trace = []): bool
    {
        return $this->update($id, [
            'status' => NotificationStatus::FAILED->value,
            'failed_at' => now(),
            'error_message' => $error,
            'error_trace' => $trace
        ]) !== null;
    }

    public function incrementAttempts(int $id): bool
    {
        return Notification::where('id', $id)
            ->increment('attempts') > 0;
    }

    public function getReadyNotifications(int $limit = 100): Collection
    {
        return Notification::where('status', NotificationStatus::PENDING->value)
            ->where(function ($query) {
                $query->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', now());
            })
            ->limit($limit)
            ->get();
    }
}
