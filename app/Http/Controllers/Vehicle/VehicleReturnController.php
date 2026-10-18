<?php

namespace App\Http\Controllers\Vehicle;

use App\Exceptions\BookingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleReturnRequest;
use App\Http\Resources\Rental\VehicleReturnResource;
use App\Services\Vehicle\VehicleReturnService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;

class VehicleReturnController extends Controller
{
    public function __invoke(StoreVehicleReturnRequest $request, int $id, VehicleReturnService $returnService): JsonResponse
    {
        try {
            $return = $returnService->returnVehicle($id, $request->validated());

            return response()->json([
                'message' => 'Vehicle returned successfully.',
                'data'    => new VehicleReturnResource($return),
            ], 201);

        } catch (BookingException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status());
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Booking not found.'], 404);
        } catch (UniqueConstraintViolationException $e) {
            // خط الدفاع الأخير: القيد unique على rental_booking_id
            return response()->json(['message' => 'Vehicle already returned for this booking.'], 409);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to return vehicle.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
