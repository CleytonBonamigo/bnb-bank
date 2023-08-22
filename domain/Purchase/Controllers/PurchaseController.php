<?php

namespace Turno\Purchase\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Turno\Common\Traits\Response;
use Turno\Common\Traits\TransformerUtility;
use Turno\Models\User;
use Turno\Purchase\Contracts\PurchaseServiceInterface;
use Turno\Purchase\Requests\PurchaseRequest;
use Turno\Transaction\Transformers\TransactionTransformer;

class PurchaseController extends Controller {
    use Response, TransformerUtility;

    /**
     * @param PurchaseServiceInterface $service
     * @param TransactionTransformer $transformer
     */
    public function __construct(
        private PurchaseServiceInterface $service,
        private TransactionTransformer $transformer
    ){}

    /**
     * @param PurchaseRequest $request
     * @return JsonResponse
     */
    public function store(PurchaseRequest $request): JsonResponse
    {
        $transaction = $this->service->purchase($request->validated());

        return $this->successResponse([
            'transaction' => $this->transform($transaction, false),
            'balance' => auth()->user()->balance
        ]);
    }
}
