<?php

namespace App\Facades;

use App\Enums\ParcelStatusEnum;
use App\Models\Parcel;
use App\Services\DeliveryCourierService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static LengthAwarePaginator getPendingParcels(null|int $page = 1, null|int $perPage = 10):
 * @method static LengthAwarePaginator getDeliveryCourierParcels(int $deliveryCourierId, null|int $page = 1, null|int $perPage = 10, null|ParcelStatusEnum $status = null):
 * @method static Parcel goToSource(string $parcelUuid, int $deliveryId):
 * @method static Parcel goToDestination(string $parcelUuid, int $deliveryId,):
 * @method static Parcel deliverParcel(string $parcelUuid, int $deliveryId):
 * @method static Parcel assignParcel(int $deliveryId, string $uuid):
 */
class DeliveryFacade extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return DeliveryCourierService::class;
    }
}