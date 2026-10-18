<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReturn extends Model
{

use HasFactory;
    protected $guarded = [
        'id',
        'actual_return_date',
        'consumed_mileage',
        'actual_total_due_amount',
    ];

     protected function casts(): array
    {
        return [
            'actual_return_date' => 'date',
        ];
    }

    public function rentalBooking(){
        return $this->belongsTo(RentalBooking::class);
    }
}
