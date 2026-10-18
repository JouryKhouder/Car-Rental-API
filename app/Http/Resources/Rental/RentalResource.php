<?php

namespace App\Http\Resources\Rental;

use App\Http\Resources\User\CustomerResource;
use App\Http\Resources\Vehicle\VehicleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'start_date'         => $this->rental_start_date?->format('Y-m-d'),
            'end_date'           => $this->rental_end_date?->format('Y-m-d'),
            'actual_return_date' => $this->vehicleReturn?->actual_return_date?->format('Y-m-d'),
            'total_price'        => (float) $this->initial_total_due_amount,
            'status'             => $this->vehicleReturn ? 'completed' : ($this->rental_start_date?->isFuture() ? 'pending' : 'active'),

            'customer'           => new CustomerResource($this->whenLoaded('customer')),
            'vehicle'            => new VehicleResource($this->whenLoaded('vehicle')),

            'created_at'         => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
