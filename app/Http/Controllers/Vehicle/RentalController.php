<?php

namespace App\Http\Controllers\Vehicle;

use App\Exceptions\BookingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingCarRequest;
use App\Http\Resources\Rental\RentalResource;
use App\Services\Vehicle\RentalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class RentalController extends Controller
{
    public function __construct(private readonly RentalService $bookingService)
    {
    }

    public function __invoke(BookingCarRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking(
                $request->validated(),
                (int) Auth::id()
            );

            return response()->json([
                'message' => 'Vehicle booked successfully.',
                'data' => new RentalResource($booking),
            ], 201);
        } catch (BookingException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status());
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }
}
