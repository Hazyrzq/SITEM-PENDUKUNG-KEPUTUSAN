<?php

namespace App\Providers;

use App\Services\MAIRCAService;
use App\Services\ROCService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Daftarkan ROCService
        $this->app->singleton(ROCService::class, function ($app) {
            return new ROCService();
        });

        // Daftarkan MAIRCAService
        $this->app->singleton(MAIRCAService::class, function ($app) {
            return new MAIRCAService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}