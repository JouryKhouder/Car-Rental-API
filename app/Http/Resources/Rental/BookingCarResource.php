<?php

namespace App\Http\Resources\Rental;

use App\Http\Resources\Vehicle\VehicleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingCarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'start_date'       => $this->rental_start_date?->format('Y-m-d'),
            'end_date'         => $this->rental_end_date?->format('Y-m-d'),
            'rental_days'      => (int) $this->initial_rental_days,
            'price_per_day'    => (float) $this->rental_price_per_day,
            'total_price'      => (float) $this->initial_total_due_amount,
            'pickup_location'  => $this->pickup_location,
            'dropoff_location' => $this->dropoff_location,
            'check_notes'      => $this->initial_check_notes,

            'vehicle'          => new VehicleResource($this->whenLoaded('vehicle')),
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}