<?php

use App\Http\Controllers\API\V1\Business\BusinessParcelController;
use App\Http\Controllers\API\V1\Delivery\DeliveryCourierParcelController;
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

Route::prefix('v1')->name('v1.')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('delivery')
        ->name('delivery.')
        ->middleware(['ability:delivery'])
        ->controller(DeliveryCourierParcelController::class)
        ->group(function () {
            Route::prefix('parcels')->name('parcel.')->group(function () {
                Route::get('pending', 'pendingParcels')->name('pending');
                Route::get('accepted', 'acceptedParcels')->name('accepted');
                Route::patch('{uuid}/assign', 'assignParcel')->name('assign');
                Route::patch('{uuid}/go-to-source', 'goToParcelSource')->name('go-to-parcel-source');
                Route::patch('{uuid}/go-to-destination', 'goToParcelDestination')->name('go-to-destination');
                Route::patch('{uuid}/done', 'deliverParcel')->name('deliver');
            });
        });

    Route::prefix('business')->name('business.')->middleware(['ability:business'])->group(function () {
        Route::prefix('parcels')->name('parcels.')->controller(BusinessParcelController::class)->group(function () {
            Route::post('/', 'store')->name('store');
            Route::patch('/{uuid}/cancel', 'cancel')->name('cancel');
        });
    });
});
