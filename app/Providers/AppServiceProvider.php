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
        //
        $this->app->bind(
            \App\Interfaces\paymentInterface::class,
            \App\Repository\paymentRepository::class
        );

        $this->app->bind(
            \App\Interfaces\contributionGroupInterface::class,
            \App\Repository\contributionGroupRepository::class
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
