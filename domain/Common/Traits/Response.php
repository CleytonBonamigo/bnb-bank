<?php

namespace Turno\Common\Traits;

use Illuminate\Http\JsonResponse;

trait Response {

    /**
     * @param array $data
     * @return JsonResponse
     */
    private function successResponse(array $data = []): JsonResponse
    {
        return response()->json(array_merge($data, ['success' => true]));
    }

}
