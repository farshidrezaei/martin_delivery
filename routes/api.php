<?php

use App\Http\Controllers\API\V1\Business\BusinessParcelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('business')->middleware(['ability:business'])->group(function () {
        Route::prefix('orders')->controller(BusinessParcelController::class)->group(function () {
            Route::post('/', 'store');
            Route::patch('/{uuid}/cancel', 'cancel');
        });
    });
});
