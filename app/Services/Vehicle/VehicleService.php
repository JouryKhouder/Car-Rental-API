<?php

namespace App\Services\Vehicle;

use App\Models\Vehicle;
// هاد بس للأدمن
class VehicleService
{
    public function getAllVehicles(array $filters = [])
    {
        return Vehicle::with(['carModel.make', 'category', 'fuelType', 'driveType', 'images'])
            ->filter($filters) // استدعاء الـ Scope من الموديل مباشرة
            ->latest()
            ->paginate(10);
    }

    /**
     * عرض سيارة واحدة بالتفصيل
     */
    // هي للادمن
    public function getVehicleById(int $id): Vehicle
    {
        return Vehicle::with(['carModel.make', 'category', 'fuelType', 'driveType', 'images'])
            ->findOrFail($id);
    }

    public function getAvailableVehiclesForCustomer(array $filters = [])
    {
        unset($filters['status']); // حماية لمنع الزبون من تمرير status غير متاح

        return Vehicle::with(['carModel.make', 'category', 'fuelType', 'driveType', 'images'])
            ->where('status', 'available')
            ->filter($filters)
            ->latest()
            ->paginate(10);
    }

    /**
     * جلب تفاصيل سيارة واحدة للزبون (بشرط أن تكون متاحة)
     */
    // هي للكوستمر
    public function getAvailableVehicleById(int $id): Vehicle
    {
        return Vehicle::with(['carModel.make', 'category', 'fuelType', 'driveType', 'images'])
            ->where('status', 'available')
            ->findOrFail($id);
    }

    public function createVehicle(array $data): Vehicle
    {
        $data = $this->normalizeVehicleData($data);
        $vehicle = Vehicle::create($data);
        return $vehicle->load(['carModel.make', 'category', 'fuelType', 'driveType']);
    }

    public function updateVehicle(int $id, array $data): Vehicle
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($this->normalizeVehicleData($data));

        return $vehicle->load(['carModel.make', 'category', 'fuelType', 'driveType']);
    }

    private function normalizeVehicleData(array $data): array
    {
        if (array_key_exists('car_model_id', $data)) { $data['model_id'] = $data['car_model_id']; unset($data['car_model_id']); }
        if (array_key_exists('daily_rate', $data)) { $data['rental_price_per_day'] = $data['daily_rate']; unset($data['daily_rate']); }
        return $data;
    }

    public function updateVehicleStatus(int $id, string $status): Vehicle
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update(['status' => $status]);

        return $vehicle;
    }

    public function deleteVehicle(int $id): void
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
    }
}
