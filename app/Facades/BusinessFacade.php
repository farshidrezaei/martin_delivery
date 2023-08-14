<?php

namespace App\Facades;

use App\DTOs\ParcelDTO;
use App\Models\Parcel;
use App\Services\BusinessService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Parcel storeParcel(ParcelDTO $parcelDTO);
 * @method static Parcel cancelParcel(int $businessId, string $parcelUuid);
 */
class BusinessFacade extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return BusinessService::class;
    }
}