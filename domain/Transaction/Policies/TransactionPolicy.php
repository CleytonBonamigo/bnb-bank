<?php

namespace Turno\Transaction\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy {

    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $user
     * @param $model
     * @return bool
     */
    public function show($user, $model)
    {
        if ($user->is_admin) {
            return true;
        }

        return $user->id === $model->customer_id;
    }
}
