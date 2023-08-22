<?php

namespace Turno\DepositManagement\Services;

use DB;
use Gate;
use Turno\Common\Exceptions\DomainException;
use Turno\DepositManagement\Contracts\DepositManagementInterface;
use Turno\Models\Transaction;
use Turno\Transaction\Enums\TransactionStatus;
use Turno\Transaction\Enums\TransactionType;

class DepositManagementService implements DepositManagementInterface {

    /**
     * @param Transaction $model
     */
    public function __construct(
        private Transaction $model
    )
    {
    }

    /**
     * @param int $transaction_id
     * @return bool
     */
    public function approve(int $transaction_id): bool
    {
        Gate::authorize('can-approve-deposit');

        return DB::transaction(function () use ($transaction_id) {
            $transaction = $this->model->where('type_id', TransactionType::DEPOSIT->value)
                                       ->where('id', $transaction_id)
                                       ->first();

            $this->validate($transaction);

            $transaction->update(['status_id' => TransactionStatus::APPROVED->value]);

            $transaction->customer->update([
                'balance' => $transaction->customer->balance + $transaction->amount
            ]);

            return true;
        });
    }

    /**
     * @param int $transaction_id
     * @return bool
     */
    public function reject(int $transaction_id): bool
    {
        Gate::authorize('can-reject-deposit');

        return DB::transaction(function () use ($transaction_id) {
            $transaction = $this->model->where('type_id', TransactionType::DEPOSIT->value)
                                       ->where('id', $transaction_id)
                                       ->first();

            $this->validate($transaction);

            $transaction->update(['status_id' => TransactionStatus::REJECTED->value]);

            return true;
        });
    }

    /**
     * @param Transaction|null $transaction
     * @return void
     */
    private function validate(Transaction $transaction = null): void
    {
        throw_unless($transaction, new DomainException(__('errors.transaction_not_found'), 404));

        throw_if($transaction->status_id === TransactionStatus::APPROVED->value, new DomainException(__('errors.transaction_previously_approved')));
        throw_if($transaction->status_id === TransactionStatus::REJECTED->value, new DomainException(__('errors.transaction_previously_rejected')));
    }
}
