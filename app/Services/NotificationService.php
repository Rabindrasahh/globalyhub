<?php

namespace App\Services;

use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private const RECENT_TTL = 60;
    private const SUMMARY_TTL = 120;

    public function __construct(
        private NotificationRepositoryInterface $repository
    ) {}

    /**
     * Get recent notifications for a user
     */
    public function getRecent(int $userId)
    {
        $cacheKey = "recent_user_{$userId}";

        return Cache::remember($cacheKey, self::RECENT_TTL, function () use ($userId, $cacheKey) {
            Log::info("[CACHE SET] Storing recent notifications in cache key='{$cacheKey}'");
            return $this->repository->getUserNotifications($userId);
        });
    }

    /**
     * Get notification summary
     */
    public function getSummary(?int $tenantId = null)
    {
        $cacheKey = "summary_{$tenantId}";

        return Cache::remember($cacheKey, self::SUMMARY_TTL, function () use ($tenantId, $cacheKey) {
            Log::info("[CACHE SET] Storing summary in cache key='{$cacheKey}'");
            return $this->repository->getStats($tenantId);
        });
    }

    /**
     * Clear cache for a user or tenant
     */
    public function clearCache(?int $userId = null, ?int $tenantId = null): void
    {
        if ($userId) {
            $cacheKey = "recent_user_{$userId}";
            Cache::forget($cacheKey);
            Log::warning("[CACHE CLEARED] Recent notifications cache cleared for user_id={$userId}");
        }
        if ($tenantId) {
            $cacheKey = "summary_{$tenantId}";
            Cache::forget($cacheKey);
            Log::warning("[CACHE CLEARED] Notification summary cache cleared for tenant_id={$tenantId}");
        }
    }
}
