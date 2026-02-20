<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\Interfaces\NotificationTemplateRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Repositories\NotificationTemplateRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            NotificationTemplateRepositoryInterface::class,
            NotificationTemplateRepository::class
        );
        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
