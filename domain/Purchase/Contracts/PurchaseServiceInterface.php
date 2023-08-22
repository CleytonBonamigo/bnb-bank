<?php

namespace Turno\Purchase\Contracts;

use Turno\Models\Transaction;

interface PurchaseServiceInterface
{
    public function purchase(array $data): Transaction;
}