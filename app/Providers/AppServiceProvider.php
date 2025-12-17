<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
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
        // Register broadcasting auth route and load channel definitions
        Broadcast::routes();
        if (file_exists(base_path('routes/channels.php'))) {
            require base_path('routes/channels.php');
        }
    }
}
