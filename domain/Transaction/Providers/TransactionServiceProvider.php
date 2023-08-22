<?php

namespace Turno\Transaction\Providers;

use Illuminate\Support\ServiceProvider;
use Turno\Transaction\Contracts\TransactionRepositoryInterface;
use Turno\Transaction\Repositories\TransactionRepository;

class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
