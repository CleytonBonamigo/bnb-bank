<?php

namespace Turno\Transaction\Repositories;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Turno\Common\Traits\TransformerUtility;
use Turno\Models\Transaction;
use Turno\Transaction\Contracts\TransactionRepositoryInterface;
use Turno\Transaction\Transformers\TransactionTransformer;

class TransactionRepository implements TransactionRepositoryInterface {
    use TransformerUtility, AuthorizesRequests;

    /**
     * @param Transaction $model
     * @param TransactionTransformer $transformer
     */
    public function __construct(
        private Transaction            $model,
        private TransactionTransformer $transformer
    )
    {
    }

    /**
     * @param int $transaction_id
     * @return array|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $transaction_id): ?array
    {
        $transaction = $this->model->where('id', $transaction_id)
                                   ->first();

        $this->authorize('show', $transaction);

        return $this->transform($transaction, false);
    }

    /**
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array
    {
        $customer_id = $filters['customer_id'] ?? 0;
        $type_id     = $filters['type_id'] ?? 0;
        $status_id   = $filters['status_id'] ?? 0;

        if (!auth()->user()->is_admin) {
            $customer_id = auth()->user()->id;
        }

        $data = $this->model->with('type', 'status', 'customer', 'check')
            ->when($customer_id, fn($q) => $q->where('customer_id', $customer_id))
            ->when($type_id, fn($q) => $q->where('type_id', $type_id))
            ->when($status_id, fn($q) => $q->where('status_id', $status_id))
            ->latest()
            ->get();

        return $this->transform($data);
    }
}
