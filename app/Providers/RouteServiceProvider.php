<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur ke "home" untuk aplikasi Anda.
     *
     * Jalur ini digunakan oleh autentikasi Laravel untuk mengalihkan pengguna setelah login.
     * Di sini kita mengubahnya dari '/home' menjadi '/dashboard'.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Tentukan pemetaan rute untuk aplikasi Anda.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}