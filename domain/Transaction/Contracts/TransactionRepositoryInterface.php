<?php

namespace Turno\Transaction\Contracts;

interface TransactionRepositoryInterface
{
    public function show(int $transaction_id): array|null;
    public function list(array $filters = []): array;
}