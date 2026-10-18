<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\AdminResource;
use App\Services\Auth\AdminRegisterService;
use Exception;
use Illuminate\Http\Request;

class AdminRegisterController extends Controller
{

    public function __invoke(RegisterRequest $request, AdminRegisterService $service)
    {
        try {
            $result = $service->Register($request->validated());

            return response()->json([
                'message'      => 'Acount registered successfully',
                'access_token' => $result['token'],
                'token_type'   => 'Bearer',
                'admin' => new AdminResource($result['user']),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to register user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
