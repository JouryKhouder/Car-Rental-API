<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriveType extends Model
{
    public function getNameAttribute(): string { return (string) $this->type_name; }
    use HasFactory;
    protected $guarded =[
        'id'
    ];

    public function vehicle(){
        return $this->hasMany(Vehicle::class);
    }
}
