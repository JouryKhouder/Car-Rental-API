<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Customer\CustomerRentalService;
use Exception;

class CustomerRentalController extends Controller
{
    protected $rentalService;

    public function __construct(CustomerRentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }


    public function customerHistory(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $history = $this->rentalService->getCustomerHistory($userId);

            return response()->json([
                'message' => 'The booking record was successfully retrieved',
                'data' => $history
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the booking record',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;

        $receipt = $this->rentalService->getReceiptDetails($userId, $id);

        if (!$receipt) {
            return response()->json([
                'message' => 'The booking does not exist, or you do not have permission to access it.'
            ], 404);
        }

        return response()->json([
            'message' => 'Booking details have been successfully retrieved.',
            'receipt' => $receipt
        ], 200);


    }
}
