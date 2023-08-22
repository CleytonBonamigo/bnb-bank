<?php

namespace Turno\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Turno\Auth\Requests\RegisterRequest;
use Turno\Common\Traits\Response;
use Turno\Customer\Contracts\CustomerRegistrationInterface;
use Turno\Customer\Services\CustomerRegistrationService;

class RegisterController extends Controller {
    use Response;

    private CustomerRegistrationInterface $service;

    /**
     * @param CustomerRegistrationInterface $service
     */
    public function __construct(CustomerRegistrationInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        $user = $this->service->registration($request->validated());

        return $this->successResponse(['user' => $user]);
    }
}
