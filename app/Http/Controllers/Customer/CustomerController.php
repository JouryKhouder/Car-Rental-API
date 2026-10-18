<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\User\CustomerResource;
use App\Services\Customer\CRUDCustomerService;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    public function update(UpdateCustomerRequest $request, CRUDCustomerService $customerService)
    {
        $user_id = Auth::user()->id;
        $updated = $customerService->updateCustomer($request->validated(), $user_id);

        return response()->json([
            'message' => 'Customer updated successfully.',
            'data' => new CustomerResource($updated),
        ], 200);
    }

    public function delete(CRUDCustomerService $customerService)
    {
        $userId = Auth::id();
        $customerService->deleteCustomerWithUser($userId);

        return response()->json([
            'message' => 'User and customer accounts deleted successfully.'
        ], 200);
    }

    public function show(CRUDCustomerService $customerService)
    {
        $userId = Auth::id();
        $userWithCustomer = $customerService->showCustomerInfo($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer information retrieved successfully.',
            'data' => new CustomerResource($userWithCustomer['user'])
        ], 200);
    }
}
