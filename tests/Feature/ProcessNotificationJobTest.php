<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use Mockery;
use App\Jobs\ProcessNotificationJob;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class ProcessNotificationJobTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_marks_notification_as_sent_on_success()
    {
        $notificationId = 1;

        $repository = Mockery::mock(NotificationRepositoryInterface::class);
        $repository->shouldReceive('markAsSent')
            ->once()
            ->with($notificationId)
            ->andReturn(true);

        $repository->shouldReceive('incrementAttempts')->never();
        $repository->shouldReceive('markAsFailed')->never();

        $job = new ProcessNotificationJob($notificationId);

        $job->handle($repository);
    }

    public function test_it_handles_exception_and_marks_as_failed()
    {
        $notificationId = 2;

        // Mock the repository
        $repository = Mockery::mock(NotificationRepositoryInterface::class);
        $repository->shouldReceive('markAsSent')
            ->once()
            ->with($notificationId)
            ->andThrow(new \Exception('Test exception'));

        $repository->shouldReceive('incrementAttempts')
            ->once()
            ->with($notificationId)
            ->andReturn(true);

        $repository->shouldReceive('markAsFailed')
            ->once()
            ->withArgs(function ($id, $message, $trace) use ($notificationId) {
                return $id === $notificationId && $message === 'Test exception';
            })
            ->andReturn(true);

        $job = new ProcessNotificationJob($notificationId);

        $job->handle($repository);
    }
}
