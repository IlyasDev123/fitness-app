<?php

namespace App\Providers;

use App\Services\WorkoutService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\WorkoutServiceInterface;

class WorkoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            WorkoutServiceInterface::class,
            WorkoutService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
