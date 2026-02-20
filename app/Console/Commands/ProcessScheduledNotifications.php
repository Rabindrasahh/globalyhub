<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessNotificationJob;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class ProcessScheduledNotifications extends Command
{
    protected $signature = 'notifications:process';
    protected $description = 'Process scheduled notifications';

    private NotificationRepositoryInterface $repository;

    public function __construct(NotificationRepositoryInterface $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function handle()
    {
        $notifications = $this->repository->getReadyNotifications();

        foreach ($notifications as $notification) {
            ProcessNotificationJob::dispatch($notification->id);
        }

        $this->info(count($notifications) . " notifications dispatched.");
    }
}
