<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleStatusRequest;
use App\Http\Requests\FilterVehicleRequest;
use App\Http\Resources\Vehicle\VehicleResource;
use App\Models\Vehicle;
use App\Services\Vehicle\VehicleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Exception;
use GuzzleHttp\Psr7\Request;

class VehicleController extends Controller
{
    public function __construct(private VehicleService $vehicleService)
    {
    }

    public function index(FilterVehicleRequest $request): JsonResponse
    {
        try {
            $vehicles = $this->vehicleService->getAllVehicles($request->validated());

            return response()->json([
                'message' => 'Vehicles retrieved successfully.',
                'data' => VehicleResource::collection($vehicles)->response()->getData(true),
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch vehicles.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->getVehicleById($id);
            return response()->json([
                'data' => new VehicleResource($vehicle)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehicle not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve vehicle.', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->createVehicle($request->validated());
            return response()->json([
                'message' => 'Vehicle created successfully.',
                'data' => new VehicleResource($vehicle)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create vehicle.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateVehicleRequest $request, int $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->updateVehicle($id, $request->validated());
            return response()->json([
                'message' => 'Vehicle updated successfully.',
                'data' => new VehicleResource($vehicle)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehicle not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update vehicle.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(UpdateVehicleStatusRequest $request, int $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->updateVehicleStatus($id, $request->validated()['status']);
            return response()->json([
                'message' => 'Vehicle status updated successfully.',
                'data' => new VehicleResource($vehicle)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehicle not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update vehicle status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->vehicleService->deleteVehicle($id);
            return response()->json([
                'message' => 'Vehicle deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehicle not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete vehicle.', 'error' => $e->getMessage()], 500);
        }
    }
}
