<?php

namespace Turno\Deposit\Contracts;

use Turno\Models\Transaction;

interface DepositServiceInterface
{
    public function deposit(array $params): Transaction;
}