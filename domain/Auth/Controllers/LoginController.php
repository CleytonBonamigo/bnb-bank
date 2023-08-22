<?php

namespace Turno\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Turno\Auth\Requests\LoginRequest;
use Turno\Common\Traits\Response;

class LoginController extends Controller {

    use Response;

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        if ( ! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        $token = auth()->user()->createToken('authtoken');

        return $this->successResponse(['user' => auth()->user(), 'token' => $token->plainTextToken]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        if (($token = auth()->user()?->currentAccessToken()) && isset($token->id)) {
            $token->delete();
        }

        auth()->guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return $this->successResponse();
    }
}
