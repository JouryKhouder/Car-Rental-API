<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalBooking extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'customer_id',
        'initial_rental_days',
        'rental_price_per_day',
        'initial_total_due_amount',
        'initial_check_notes'
    ];

    // تحويل التواريخ إلى Carbon (مطلوب لل Resource)
    protected function casts(): array
    {
        return [
            'rental_start_date' => 'date',
            'rental_end_date'   => 'date',
        ];
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    public function vehicleReturn(){
        return $this->hasOne(VehicleReturn::class, 'rental_booking_id');
    }

    public function rentalTransaction(){
        return $this->hasMany(RentalTransaction::class, 'booking_id');
    }
}
