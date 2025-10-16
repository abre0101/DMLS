<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Load api routes
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace) // optional in Laravel 8+
                ->group(base_path('routes/api.php'));

            // Load web routes
            Route::middleware('web')
                ->namespace($this->namespace) // optional in Laravel 8+
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // You can configure API rate limiting here if you want
    }
}
