<?php

namespace Turno\Transaction\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Turno\Models\Transaction;
use Turno\Transaction\Enums\TransactionType;

class TransactionTransformer extends TransformerAbstract {

    /**
     * @param Transaction $model
     * @return array
     */
    public function transform(Transaction $model): array
    {
        return [
            'id' => $model->id,
            'status_id' => $model->status_id,
            'status_description' => $model->status->description,
            'type_id' => $model->type_id,
            'type_description' => $model->type->description,
            'customer_id' => $model->customer_id,
            'customer_account' => str_pad($model->customer_id, 12, '0', \STR_PAD_LEFT),
            'customer_name' => $model->customer->name,
            'customer_email' => $model->customer->email,
            'amount' => number_format($model->amount, 2, '.', ''),
            'amount_formatted' => number_format($model->amount, 2, ',', ''),
            'description' => $model->description,
            'check_url' => $model->type_id === TransactionType::DEPOSIT->value ? $model->check?->url : null,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
            'date_formatted' => Carbon::parse($model->created_at)->isoFormat('MM/DD/Y h:mm a')
        ];
    }
}
