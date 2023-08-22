<?php

namespace Turno\Purchase\Services;

use DB;
use Gate;
use Illuminate\Validation\ValidationException;
use Turno\Models\Transaction;
use Turno\Transaction\Enums\TransactionStatus;
use Turno\Transaction\Enums\TransactionType;
use Turno\Purchase\Contracts\PurchaseServiceInterface;

class PurchaseService implements PurchaseServiceInterface {

    /**
     * @param Transaction $model
     */
    public function __construct(
        private Transaction $model
    ){}

    /**
     * @param array $data
     * @return Transaction
     * @throws ValidationException
     */
    public function purchase(array $data): Transaction
    {
        Gate::authorize('can-purchase');

        if (auth()->user()->balance < $data['amount']) {
            throw ValidationException::withMessages([
                'amount' => __('errors.enough_money'),
            ]);
        }

        return DB::transaction(function () use ($data) {
            $transaction = $this->model->create([
                'status_id' => TransactionStatus::APPROVED->value,
                'type_id' => TransactionType::PURCHASE->value,
                'customer_id' => auth()->user()->id,
                'amount' => ($data['amount'] * -1),
                'description' => $data['description'],
            ]);

            auth()->user()->update([
                'balance' => auth()->user()->balance - $data['amount']
            ]);

            return $transaction;
        });
    }
}
