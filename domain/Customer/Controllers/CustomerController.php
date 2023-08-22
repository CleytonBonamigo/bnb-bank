<?php

namespace Turno\Customer\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\JsonResponse;
use Storage;
use Turno\Common\Traits\Response;

class CustomerController extends Controller {
    use Response;

    /**
     * @return JsonResponse
     */
    public function balance(): JsonResponse
    {
        Gate::authorize('can-view-own-balance');

        return $this->successResponse(['balance' => auth()->user()->balance]);
    }
}
