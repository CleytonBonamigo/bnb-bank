<?php

namespace Turno\DepositManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Turno\DepositManagement\Contracts\DepositManagementInterface;
use Turno\DepositManagement\Services\DepositManagementService;

class DepositManagementServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DepositManagementInterface::class, DepositManagementService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
