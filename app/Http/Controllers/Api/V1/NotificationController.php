<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Exception;


class NotificationController extends Controller
{
    public function __construct(
        private NotificationRepositoryInterface $repository
    ) {}

    /**
     * List all notifications (optional: add pagination later)
     */
    public function index(): JsonResponse
    {

        try {
            $notifications = $this->repository->getPendingNotifications(100);
            return $this->success($notifications, 'Notification Fetched Successfully');
        } catch (Exception $e) {
            return  $this->error('Failed to fetch notifications', $e->getCode(), $e->getTrace());
        }
    }

    /**
     * Create a new notification
     */
    public function store(StoreNotificationRequest $request): JsonResponse
    {
        try {
            $notification = $this->repository->create($request->validated());
            return $this->success($notification, 'Notification Created Successfully', 201);
        } catch (Exception $e) {
            return $this->error('Failed to create notification', $e->getCode(), $e->getTrace());
        }
    }

    /**
     * Show a single notification
     */
    public function show(int $id): JsonResponse
    {
        try {
            $notification = $this->repository->find($id);

            if (!$notification) {
                return $this->error('Not Found', 404);
            }

            return $this->success($notification, 'Notification Fetched Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to fetch notification', $e->getCode(), $e->getTrace());
        }
    }

    /**
     * Update a notification
     */
    public function update(StoreNotificationRequest $request, int $id): JsonResponse
    {
        try {
            $notification = $this->repository->update($id, $request->validated());

            if (!$notification) {
                return $this->error('Not Found', 404);
            }

            return $this->success($notification, 'Notification Updated Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to update notification', $e->getCode(), $e->getTrace());
        }
    }

    /**
     * Delete a notification
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->repository->delete($id);

            if (!$deleted) {
                return $this->error('Not Found', 404);
            }

            return $this->success([], 'Notification Deleted Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to delete notification', $e->getCode(), $e->getTrace());
        }
    }

    /**
     * Recent notifications for a user
     */
    public function recent(int $userId): JsonResponse
    {
        try {
            $notifications = $this->repository->getUserNotifications($userId);
            return $this->success($notifications, 'Recent Notifications Fetched Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to fetch recent notifications', $e->getCode(), $e->getTrace());
        }
    }

    /**
     * Notification summary (total, pending, sent, failed)
     */
    public function summary(?int $tenantId = null): JsonResponse
    {
        try {
            $stats = $this->repository->getStats($tenantId);
            return $this->success($stats, 'Notification Summary Fetched Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to fetch summary', $e->getCode(), $e->getTrace());
        }
    }
}
