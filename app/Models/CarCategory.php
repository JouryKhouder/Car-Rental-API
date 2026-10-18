<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    public function getNameAttribute(): string { return (string) $this->catygory_name; }
    use HasFactory;
    protected $guarded = ['id'];
}
