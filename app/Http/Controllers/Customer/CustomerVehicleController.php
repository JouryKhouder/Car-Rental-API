<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterVehicleRequest;
use App\Http\Resources\Vehicle\VehicleResource;
use App\Services\Vehicle\VehicleService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CustomerVehicleController extends Controller
{
    public function __construct(private VehicleService $vehicleService) {}

    public function index(FilterVehicleRequest $request): JsonResponse
    {
        try {
            $vehicles = $this->vehicleService->getAvailableVehiclesForCustomer($request->validated());

            return response()->json([
                'message' => 'Available vehicles retrieved successfully.',
                'data'    => VehicleResource::collection($vehicles)->response()->getData(true),
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch vehicles.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->getAvailableVehicleById($id);

            return response()->json([
                'message' => 'Vehicle details retrieved successfully.',
                'data'    => new VehicleResource($vehicle),
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Vehicle not found or currently unavailable.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch vehicle details.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
