<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];

    public function vehicles(){
        return $this->hasMany(Vehicle::class, 'model_id');
    }

    public function vehicle(){
        return $this->vehicles();
    }

    public function make(){
        return $this->belongsTo(Make::class);
    }
}
