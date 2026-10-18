<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'plate_number' => $this->plate_number,
            'year'         => (int) $this->year,
            'color'        => $this->color,
            'seats'        => (int) $this->seats,
            'transmission' => $this->transmission,
            'daily_rate'   => (float) $this->rental_price_per_day,
            'status'       => $this->status, // available, rented, maintenance

            'make'         => $this->carModel?->make?->name,
            'model'        => $this->carModel?->name,
            'full_title'   => trim(($this->carModel?->make?->name ?? '') . ' ' . ($this->carModel?->name ?? '') . ' ' . $this->year),

            // البيانات التابعة الأخرى (Lookup Values)
            'category'     => $this->category?->name,
            'fuel_type'    => $this->fuelType?->name,
            'drive_type'   => $this->driveType?->name,

            'identifiers'  => [
                'car_model_id'    => $this->model_id,
                'car_category_id' => $this->car_category_id,
                'fuel_type_id'    => $this->fuel_type_id,
                'drive_type_id'   => $this->drive_type_id,
            ],

            'images'       => $this->whenLoaded('images', function () {
                return $this->images->pluck('car_image');
            }),

            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
