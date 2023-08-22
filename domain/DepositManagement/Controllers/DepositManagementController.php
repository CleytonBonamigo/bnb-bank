<?php

namespace Turno\DepositManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Turno\Common\Traits\Response;
use Turno\DepositManagement\Contracts\DepositManagementInterface;
use Turno\DepositManagement\Services\DepositManagementService;

class DepositManagementController extends Controller {
    use Response;

    /**
     * @param DepositManagementInterface $service
     */
    public function __construct(
        private DepositManagementInterface $service
    ){}

    /**
     * @param int $transaction_id
     * @return JsonResponse
     */
    public function approve(int $transaction_id): JsonResponse
    {
        $this->service->approve($transaction_id);

        return $this->successResponse();
    }

    /**
     * @param int $transaction_id
     * @return JsonResponse
     */
    public function reject(int $transaction_id): JsonResponse
    {
        $this->service->reject($transaction_id);

        return $this->successResponse();
    }
}
