<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Customer\CustomerRentalController;
use App\Http\Controllers\Vehicle\VehicleController;
use App\Http\Controllers\Vehicle\VehicleLookupController;
use App\Http\Controllers\Vehicle\VehicleReturnController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerVehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vehicle\BookingCarController;
use App\Http\Controllers\Vehicle\RentalController;
use App\Http\Controllers\Vehicle\VehicleImageController;

/*
|--------------------------------------------------------------------------
| 1. المسارات العامة (Public Routes - بدون توكن)
|--------------------------------------------------------------------------
*/

Route::post('auth/register', CustomerRegisterController::class);
// الحد الأقصى لمحاولات تسجيل الدخول: 5 محاولات في الدقيقة
Route::post('/login', LoginController::class)->middleware('throttle:5,1');

Route::post('auth/register/partner', AdminRegisterController::class);
/*
|--------------------------------------------------------------------------
| 2. المسارات المحمية بتسجيل الدخول (Authenticated Routes)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // بيانات المستخدم الحالي وتسجيل الخروج (مشترك للجميع)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', LogoutController::class);
    Route::put('auth/password', [ChangePasswordController::class, 'updatePassword']);

    /*
    |----------------------------------------------------------------------
    | مسارات الزبون (Customer Routes)
    |----------------------------------------------------------------------
    */
    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'show']);
        Route::put('/update', [CustomerController::class, 'update']);
        Route::delete('/delete', [CustomerController::class, 'delete']);

        Route::post('/rentals', RentalController::class);
        //  [مسار جديد]: عرض حجوزات الزبون الحالي
        // Route::get('/rentals', [CustomerBookingController::class, 'index']);

        Route::get('/vehicles', [CustomerVehicleController::class, 'index']);
        Route::get('/vehicles/{id}', [CustomerVehicleController::class, 'show']);

        // 1. إنشاء حجز جديد (استعارة سيارة)
        Route::post('/rentals', RentalController::class);

        // مسار عرض كل السجل للسيارات المستعارة من قبل الزبون الحالي
        Route::get('/my-rentals', [CustomerRentalController::class, 'customerHistory']);

        // مسار عرض إيصال محدد لسيارة مستعارة من قبل الزبون الحالي
        Route::get('/my-rentals/{id}', [CustomerRentalController::class, 'show']);

    });

    /*
    |----------------------------------------------------------------------
    | مسارات الأدمن فقط (Admin Only Routes)
    | محمية بـ auth:sanctum (من الغروب الأب) + ميدلوير is_Admin
    |----------------------------------------------------------------------
    */
    Route::middleware('is_Admin')->prefix('admin')->group(function () {

        Route::get('/profile', [AdminController::class, 'show']);
        Route::put('/update', [AdminController::class, 'update']);

        // إدارة الزبائن
        Route::get('/show/customers', [AdminController::class, 'showAllCustomers']);
        Route::get('/show/customers/{customer_id}', [AdminController::class, 'showCustomer']);

        //  [مسار جديد]: حذف زبون من قبل الأدمن
        // Route::delete('/customers/{id}', [AdminController::class, 'deleteCustomer']);

        // مسارات القوائم المنسدلة وإضافة الماركات والموديلات (تم تصحيح الخطأ المطبعي هنا)
        Route::get('/lookups', [VehicleLookupController::class, 'index']);
        Route::get('/makes/{makeId}/models', [VehicleLookupController::class, 'getModelsByMake']);
        Route::post('/makes', [VehicleLookupController::class, 'storeMake']);
        Route::post('/car-models', [VehicleLookupController::class, 'storeCarModel']);

        // مسار تعديل حالة السيارة فقط (PATCH)
        Route::patch('/vehicles/{vehicle}/status', [VehicleController::class, 'updateStatus']);

        // مسارات الـ CRUD الخمسة للسيارات (index, store, show, update, destroy)
        Route::apiResource('vehicles', VehicleController::class);

        // إدارة الحجوزات
        //  [مسار جديد]: عرض جميع الحجوزات في النظام للأدمن
        // Route::get('/rentals', [AdminBookingController::class, 'index']);

        // Route::post('/rental/{vehicle_id}', RentalController::class);
        // Route::post('/rentals/{id}/return', VehicleReturnController::class);
        Route::post('/rentals/{booking_id}/return', VehicleReturnController::class);

        Route::post('/vehicles/{id}/images', [VehicleImageController::class, 'store']);
    });
});
