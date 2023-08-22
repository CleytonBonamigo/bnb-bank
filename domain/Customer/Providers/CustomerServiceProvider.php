<?php

namespace Turno\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Turno\Customer\Contracts\CustomerRegistrationInterface;
use Turno\Customer\Services\CustomerRegistrationService;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerRegistrationInterface::class, CustomerRegistrationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
