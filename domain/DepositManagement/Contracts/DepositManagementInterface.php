<?php

namespace Turno\DepositManagement\Contracts;

interface DepositManagementInterface
{
    public function approve(int $transaction_id): bool;
    public function reject(int $transaction_id): bool;
}