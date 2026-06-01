<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Check if the application is running via an ngrok proxy or secure environment
    if (str_contains(request()->url(), 'ngrok-free.app') || config('app.env') === 'production') {
        URL::forceScheme('https');
    }
}
}

