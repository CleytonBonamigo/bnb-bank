<?php

namespace Turno\Customer\Services;

use Turno\Customer\Contracts\CustomerRegistrationInterface;
use Turno\Models\User;

class CustomerRegistrationService implements CustomerRegistrationInterface {

    /**
     * @param User $model
     */
    public function __construct(private User $model)
    {
    }

    /**
     * @param array $data
     * @return User
     */
    public function registration(array $data): User
    {
        return $this->model->create($data);
    }
}
