<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\WeatherServiceInterface::class,
            \App\Services\Weather\OpenMeteoService::class
        );
    }

    public function boot(): void
    {
        
    }
}
