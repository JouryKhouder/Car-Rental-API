<?php

namespace App\Http\Resources\Rental;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleReturnResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $booking     = $this->rentalBooking;
        $initialDays = (int) $booking?->initial_rental_days;

        return [
            'id'                 => $this->id,
            'booking_id'         => $this->rental_booking_id,
            'plate_number'       => $booking?->vehicle?->plate_number,
            'vehicle_status'     => $booking?->vehicle?->status,

            'planned_end_date'   => $booking?->rental_end_date?->format('Y-m-d'),
            'actual_return_date' => $this->actual_return_date?->format('Y-m-d'),
            'planned_days'       => $initialDays,
            'actual_days'        => (int) $this->actual_rental_days,
            'late_days'          => max(0, (int) $this->actual_rental_days - $initialDays),

            'mileage'            => (int) $this->mileage,
            'consumed_mileage'   => (int) $this->consumed_mileage,

            'additional_charges' => (float) $this->additional_charges,
            'initial_total'      => (float) $booking?->initial_total_due_amount,
            'actual_total'       => (float) $this->actual_total_due_amount,

            'final_check_notes'  => $this->final_check_notes,
            'created_at'         => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}