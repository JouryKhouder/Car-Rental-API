<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\User\AdminResource;
use App\Http\Resources\User\CustomerResource;
use App\Services\Admin\CRUDAdminService;
use Illuminate\Support\Facades\Auth;
use Exception;
class AdminController extends Controller
{
    public function show(CRUDAdminService $service)
    {
        try {
            $user = Auth::user();
            $admin = $service->show($user);
            return response()->json([
                'data' => new AdminResource($admin)
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'message' => 'Something went wrong during login.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    public function showAllCustomers(CRUDAdminService $service)
    {
        try {
            $customers = $service->showAllCustomers();

            return response()->json([
                'message' => 'Customers retrieved successfully.',
                'data' => CustomerResource::collection($customers)
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch customers.',
            ], 500);
        }
    }

    public function showCustomer($customer_id, CRUDAdminService $service)
    {
        try {
            $customer = $service->showCustomer($customer_id);

            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found.',
                ], 404);
            }

            return response()->json([
                'message' => 'Customer retrieved successfully.',
                'data' => new CustomerResource($customer)
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch customer.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateAdminRequest $request, CRUDAdminService $service)
    {
        try {

            $user = Auth::user();
            $admin = $service->update($user, $request->validated());
            return response()->json([
                'data' => new AdminResource($admin)
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'message' => 'Something went wrong during login.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}
