<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Resources\User\CustomerResource;
use App\Services\Auth\CustomerRegisterService;

use Exception;

class CustomerRegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateCustomerRequest $request, CustomerRegisterService $service)
    {
        try {
            $result = $service->Register($request->validated());

            return response()->json([
                'message'      => 'User registered successfully',
                'access_token' => $result['token'],
                'token_type'   => 'Bearer',
                'user'         => new CustomerResource($result['user']),
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to register user.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
