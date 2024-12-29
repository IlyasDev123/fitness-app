<?php

namespace App\Providers;

use App\Services\InsightService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\InsightServiceInterface;

class InsightServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(InsightServiceInterface::class, InsightService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
