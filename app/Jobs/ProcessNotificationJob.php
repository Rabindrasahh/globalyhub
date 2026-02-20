<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Models\NotificationLog;
use Exception;

class ProcessNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notificationId;

    public $tries = 3;

    public function backoff()
    {
        return [10, 30, 60];
    }

    public function __construct(int $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function handle(NotificationRepositoryInterface $repository)
    {
        $notification = $repository->find($this->notificationId);

        if ($notification->scheduled_at && $notification->scheduled_at->isFuture()) {
            return;
        }
        try {

            $repository->markAsSent($this->notificationId);

            NotificationLog::create([
                'notification_id' => $this->notificationId,
                'action' => 'sent',
                'data' => ['message' => 'Notification successfully sent.'],
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (Exception $e) {
            $repository->incrementAttempts($this->notificationId);
            $repository->markAsFailed($this->notificationId, $e->getMessage(), ['trace' => $e->getTrace()]);
            NotificationLog::create([
                'notification_id' => $this->notificationId,
                'action' => 'failed',
                'data' => ['error' => $e->getMessage()],
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        }
    }
}
