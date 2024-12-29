<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Contracts\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserAuthRepository;

class CustomAuthProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserAuthRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
