<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Turno\Models\Transaction;
use Turno\Models\User;
use Turno\Transaction\Policies\TransactionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Transaction::class => TransactionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('can-purchase', function (User $user) {
            return !$user->is_admin;
        });

        Gate::define('can-deposit', function (User $user) {
            return !$user->is_admin;
        });

        Gate::define('can-approve-deposit', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('can-reject-deposit', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('can-view-own-balance', function (User $user) {
            return !$user->is_admin;
        });
    }
}
