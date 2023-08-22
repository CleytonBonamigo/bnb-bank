<?php

namespace Turno\Deposit\Providers;

use Illuminate\Support\ServiceProvider;
use Turno\Deposit\Contracts\DepositServiceInterface;
use Turno\Deposit\Services\DepositService;

class DepositServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DepositServiceInterface::class, DepositService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
