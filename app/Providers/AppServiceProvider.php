<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind RabbitMQReceiveService to the service container
        $this->app->singleton(RabbitMQReceiveService::class, function ($app) {
            return new RabbitMQReceiveService();
        });

        // Bind RabbitMQSendService to the service container
        $this->app->singleton(RabbitMQSendService::class, function ($app) {
            return new RabbitMQSendService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
