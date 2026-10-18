<?php
namespace App\Services\Vehicle;

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class VehicleImageService
{
    public function uploadImages(int $vehicleId, array $files)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $uploadedImages = [];

        DB::transaction(function () use ($vehicle, $files, &$uploadedImages) {
            foreach ($files as $file) {
                $path = $file->store('vehicles', 'public');

                $uploadedImages[] = $vehicle->images()->create([
                    'image_path' => $path,
                    // 'is_primary' => false, // تحديث مستقبلي
                ]);
            }
        });

        return $uploadedImages;
    }
}
