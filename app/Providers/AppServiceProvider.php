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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forçar HTTPS quando em produção ou usando ngrok
        if ($this->app->environment('production') || request()->header('x-forwarded-proto') === 'https') {
            \URL::forceScheme('https');
        }
    }
}
