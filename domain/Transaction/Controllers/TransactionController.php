<?php

namespace Turno\Transaction\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Storage;
use Turno\Common\Traits\Response;
use Turno\Transaction\Repositories\TransactionRepository;

class TransactionController extends Controller {
    use Response;

    /**
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        private TransactionRepository $transactionRepository
    ){}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $filters = request()->only('type_id', 'status_id', 'customer_id');

        $data = $this->transactionRepository->list($filters);

        if ( ! auth()->user()->is_admin) {
            $data['balance'] = auth()->user()->balance;
        }

        return $this->successResponse($data);
    }

    /**
     * @param int $transaction_id
     * @return JsonResponse
     */
    public function show(int $transaction_id): JsonResponse
    {
        $transaction = $this->transactionRepository->show($transaction_id);

        return $this->successResponse(['transaction' => $transaction]);
    }

    /**
     * @param int $transaction_id
     * @return mixed
     */
    public function image(int $transaction_id)
    {
        $transaction = $this->transactionRepository->show($transaction_id);

        return Storage::download($transaction['check_url']);
    }
}
