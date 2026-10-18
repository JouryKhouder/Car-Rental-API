<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\User\AdminResource;
use App\Http\Resources\User\CustomerResource;
use App\Services\Auth\LoginService;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginService $loginService): JsonResponse
    {
        try {
            $result = $loginService->login($request->validated());

            $user = $result['user'];
            $token = $result['token'];

            $userResource = ($user->role === 'admin')
                ? new AdminResource($user)
                : new CustomerResource($user);

            return response()->json([
                'message' => 'Login successful.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $userResource,
            ], 200);

        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 401);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong during login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
