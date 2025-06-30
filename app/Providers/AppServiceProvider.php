<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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
        Route::middleware([
            EnsureFrontendRequestsAreStateful::class,
            'api'
        ])
            ->prefix('api')
            ->group(base_path('routes/api.php'));
        
    }
}

