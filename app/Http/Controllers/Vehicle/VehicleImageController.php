<?php
namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleImageRequest;
use App\Services\Vehicle\VehicleImageService;

class VehicleImageController extends Controller
{
    public function store(StoreVehicleImageRequest $request, $vehicleId, VehicleImageService $imageService)
    {
        $images = $imageService->uploadImages($vehicleId, $request->file('images'));

        return response()->json([
            'message' => 'Images uploaded successfully.',
            'data' => $images
        ], 201);
    }

    public function destroy($imageId)
    {
        // كود حذف الصورة من المجلد والداتا بيز
    }
}

