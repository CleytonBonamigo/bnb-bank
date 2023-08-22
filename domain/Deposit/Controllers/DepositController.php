<?php

namespace Turno\Deposit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Turno\Common\Traits\Response;
use Turno\Deposit\Contracts\DepositServiceInterface;
use Turno\Deposit\Requests\DepositRequest;

class DepositController extends Controller {
    use Response;

    /**
     * @param DepositServiceInterface $service
     */
    public function __construct(
        private DepositServiceInterface $service
    ){}

    /**
     * @param DepositRequest $request
     * @return JsonResponse
     */
    public function store(DepositRequest $request): JsonResponse
    {
        $transaction = $this->service->deposit($request->validated());

        return $this->successResponse(['transaction' => $transaction]);
    }
}
