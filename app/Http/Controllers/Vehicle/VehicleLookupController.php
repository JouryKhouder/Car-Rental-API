<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMakeRequest;
use App\Http\Requests\Admin\StoreCarModelRequest;
use App\Http\Resources\Vehicle\VehicleLookupResource;
use App\Services\Vehicle\VehicleLookupService;
use Illuminate\Http\JsonResponse;
use Exception;

class VehicleLookupController extends Controller
{
    public function __construct(private VehicleLookupService $lookupService) {}

    public function index(): JsonResponse
    {
        try {
            $lookups = $this->lookupService->getInitialLookups();

            return response()->json([
                'data' => [
                    'makes'       => VehicleLookupResource::collection($lookups['makes']),
                    'categories'  => VehicleLookupResource::collection($lookups['categories']),
                    'fuel_types'  => VehicleLookupResource::collection($lookups['fuel_types']),
                    'drive_types' => VehicleLookupResource::collection($lookups['drive_types']),
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch lookups.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getModelsByMake(int $makeId): JsonResponse
    {
        try {
            $models = $this->lookupService->getModelsByMake($makeId);

            return response()->json([
                'data' => VehicleLookupResource::collection($models)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch models.', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeMake(StoreMakeRequest $request): JsonResponse
    {
        try {
            $make = $this->lookupService->storeMake($request->validated());

            return response()->json([
                'message' => 'Make created successfully.',
                'data'    => new VehicleLookupResource($make)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create make.', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeCarModel(StoreCarModelRequest $request): JsonResponse
    {
        try {
            $model = $this->lookupService->storeCarModel($request->validated());

            return response()->json([
                'message' => 'Car model created successfully.',
                'data'    => new VehicleLookupResource($model)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create car model.', 'error' => $e->getMessage()], 500);
        }
    }
}
