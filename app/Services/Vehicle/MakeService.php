<?php
namespace App\Services\Vehicle;

use App\Models\Make;

class MakeService
{
    public function showVehicleMake()
    {
        $makes = Make::all();
        return $makes;
    }

    public function addVehicleMake()
    {

        return;
    }
}
