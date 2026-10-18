<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\Auth\UpdatePasswordService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    public function updatePassword(ChangePasswordRequest $request, UpdatePasswordService $service)
    {
        try {
            $service->changePassword($request->user(), $request->validated());

            return response()->json([
                'message' => 'The password has been changed successfully.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 401);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong during login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
