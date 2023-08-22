<?php

namespace Turno\Customer\Contracts;

use Turno\Models\User;

interface CustomerRegistrationInterface
{
    public function registration(array $data): User;
}