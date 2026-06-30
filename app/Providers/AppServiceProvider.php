<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/student.php'));
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/kelas.php'));
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/attendence.php'));
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/report.php'));
    }
}
