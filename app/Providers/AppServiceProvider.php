<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LibreTranslateService;
use App\Services\QuotableService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(QuotableService::class, function ($app) {
            return new QuotableService();
        });

        // Bind LibreTranslateService ke dalam container
        $this->app->singleton(LibreTranslateService::class, function ($app) {
            return new LibreTranslateService();
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
