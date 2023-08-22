<?php

namespace Turno\Deposit\Services;

use DB;
use Gate;
use Illuminate\Http\UploadedFile;
use Turno\Deposit\Contracts\DepositServiceInterface;
use Turno\Models\Transaction;
use Turno\Models\TransactionCheck;
use Turno\Transaction\Enums\TransactionStatus;
use Turno\Transaction\Enums\TransactionType;

class DepositService implements DepositServiceInterface {

    /**
     * @param Transaction $model
     * @param TransactionCheck $transactionCheck
     */
    public function __construct(
        private Transaction $model,
        private TransactionCheck $transactionCheck
    ){}

    /**
     * @param array $params
     * @return Transaction
     */
    public function deposit(array $params): Transaction
    {
        Gate::authorize('can-deposit');

        return DB::transaction(function () use ($params) {
            $transaction = $this->model->create([
                'status_id' => TransactionStatus::PENDING->value,
                'type_id' => TransactionType::DEPOSIT->value,
                'customer_id' => auth()->user()->id,
                'amount' => $params['amount'],
                'description' => $params['description'],
            ]);

            $this->transactionCheck->create([
                'transaction_id' => $transaction->id,
                'url' => $this->generateCheckUrl($params['check'])
            ]);

            return $transaction;
        });
    }

    /**
     * @param UploadedFile $file
     * @return false|string
     */
    private function generateCheckUrl(UploadedFile $file)
    {
        $name = uniqid(date('HisYmd')) . auth()->user()->id;
        $extension = $file->extension();

        return $file->storeAs('checks', "$name.$extension");
    }
}
