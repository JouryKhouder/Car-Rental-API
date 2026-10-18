<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LogoutService;
use Exception;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, LogoutService $service)
    {
        try {
            $service->Logout($request->user());

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to logout.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
