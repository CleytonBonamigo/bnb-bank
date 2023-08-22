<?php

namespace Turno\Purchase\Providers;

use Illuminate\Support\ServiceProvider;
use Turno\Purchase\Contracts\PurchaseServiceInterface;
use Turno\Purchase\Services\PurchaseService;

class PurchaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PurchaseServiceInterface::class, PurchaseService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
