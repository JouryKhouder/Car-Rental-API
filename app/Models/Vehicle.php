<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];

    public function getCarModelIdAttribute(): mixed
    {
        return $this->attributes['model_id'] ?? null;
    }

    public function getDailyRateAttribute(): mixed
    {
        return $this->attributes['rental_price_per_day'] ?? null;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            //  فلترة بالماركة (عبر علاقة الموديل Model -> Make)
            ->when($filters['make_id'] ?? null, function ($q, $makeId) {
                $q->whereHas('carModel', fn($subQuery) => $subQuery->where('make_id', $makeId));
            })

            // فلترة بالموديل (Model_id)
            ->when($filters['model_id'] ?? null, fn($q, $modelId) => $q->where('model_id', $modelId))

            //  فلترة بالفئة (Car_Category_id)
            ->when($filters['car_category_id'] ?? null, fn($q, $categoryId) => $q->where('car_category_id', $categoryId))

            //  فلترة بنوع الوقود (Fuel_Type_id)
            ->when($filters['fuel_type_id'] ?? null, fn($q, $fuelId) => $q->where('fuel_type_id', $fuelId))

            //  فلترة بنوع الدفع (Drive_type)
            ->when($filters['drive_type_id'] ?? null, fn($q, $driveId) => $q->where('drive_type_id', $driveId))

            //  فلترة بسنة الصنع (Year)
            ->when($filters['year'] ?? null, fn($q, $year) => $q->where('year', $year))

            //  فلترة باللون (color)
            ->when($filters['color'] ?? null, fn($q, $color) => $q->where('color', 'LIKE', "%{$color}%"))

            //  فلترة بالحالة (status)
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))

            //  فلترة بنطاق السعر اليومي (Rental_Price_Per_Day)
            ->when(isset($filters['min_price']), fn($q) => $q->where('rental_price_per_day', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn($q) => $q->where('rental_price_per_day', '<=', $filters['max_price']))

            //  فلترة بالحد الأقصى للكيلومترات (kilo_meterage)
            ->when(isset($filters['max_mileage']), fn($q) => $q->where('kilo_meterage', '<=', $filters['max_mileage']))

            //  البحث العام (Plate_Number أو color أو Year)
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('plate_number', 'LIKE', "%{$search}%")
                             ->orWhere('color', 'LIKE', "%{$search}%")
                             ->orWhere('year', 'LIKE', "%{$search}%");
                });
            });
    }

    public function fuelType(){
        return $this->belongsTo(FuelType::class);
    }

    public function category(){
        return $this->belongsTo(CarCategory::class, 'car_category_id');
    }

    public function carModel(){
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    public function driveType(){
        return $this->belongsTo(DriveType::class, 'drive_type_id');
    }

    public function rentalBookings(){
        return $this->hasMany(RentalBooking::class);
    }

    public function images(){
        return $this->hasMany(CarImage::class);
    }
}
