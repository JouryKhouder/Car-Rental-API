<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
    'booking_id',
    'amount',
    'payment_method',
    'payment_type',
    'transaction_date',
];

    public function rentalBooking(){
        return $this->belongsTo(RentalBooking::class, 'booking_id');
    }
}
