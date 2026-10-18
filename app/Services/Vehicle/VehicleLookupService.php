<?php

namespace App\Services\Vehicle;

use App\Models\Make;
use App\Models\CarModel;
use App\Models\FuelType;
use App\Models\DriveType;
use App\Models\CarCategory;

class VehicleLookupService
{

    // جلب كافة القوائم الثابتة والماركات لصفحة إضافة سيارة
    public function getInitialLookups(): array
    {
        return [
            'makes' => Make::orderBy('name')->get(['id', 'name']),
            'categories' => CarCategory::orderBy('catygory_name')->get(['id', 'catygory_name']),
            'fuel_types' => FuelType::orderBy('fuel_name')->get(['id', 'fuel_name']),
            'drive_types' => DriveType::orderBy('type_name')->get(['id', 'type_name']),
        ];
    }

    //  جلب الموديلات التابعة لماركة معينة فقط (عند اختيار الماركة من القائمة)
    public function getModelsByMake(int $makeId)
    {
        return CarModel::where('make_id', $makeId)
            ->orderBy('name')
            ->get(['id', 'make_id', 'name']);
    }


    // إضافة شركة مصنعة جديدة (Make)
    public function storeMake(array $data): Make
    {
        return Make::create($data);
    }

    // إضافة موديل سيارة جديد مرتبط بماركة (CarModel)
    public function storeCarModel(array $data): CarModel
    {
        return CarModel::create($data);
    }
}
