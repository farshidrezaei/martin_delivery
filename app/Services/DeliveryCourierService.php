<?php

namespace App\Services;

use App\Enums\ParcelStatusEnum;
use App\Exceptions\DeliveryCourierHasOneOnGoingParcelException;
use App\Exceptions\ParcelIsNotAcceptedException;
use App\Exceptions\ParcelIsNotOnGoingToDestinationStatusException;
use App\Exceptions\ParcelIsNotOnGoingToSourceStatusException;
use App\Models\Parcel;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeliveryCourierService
{
    public function getPendingParcels(?int $page = 1, ?int $perPage = 10): LengthAwarePaginator
    {
        $parcelQuery = Parcel::query()
            ->where('status', ParcelStatusEnum::PENDING)
            ->with(['source', 'destination', 'business']);

        return $parcelQuery->paginate(perPage: $perPage, page: $page);
    }

    public function getDeliveryCourierParcels(
        int $deliveryCourierId,
        ?int $page = 1,
        ?int $perPage = 10,
        ?ParcelStatusEnum $status = null
    ): LengthAwarePaginator {
        $parcelQuery = Parcel::query()
            ->whereDeliveryCourierId($deliveryCourierId)
            ->when($status, function (Builder $builder) use ($status) {
                $builder->where('status', $status);
            })
            ->with(['source', 'destination', 'business']);

        return $parcelQuery->paginate(perPage: $perPage, page: $page);
    }

    /**
     * @throws ParcelIsNotAcceptedException
     * @throws Throwable
     */
    public function goToSource(string $parcelUuid, int $deliveryId): Parcel
    {
        $parcel = Parcel::query()
            ->whereUuid($parcelUuid)
            ->whereDeliveryCourierId($deliveryId)
            ->whereIn('status', [ParcelStatusEnum::ACCEPTED, ParcelStatusEnum::PENDING])
            ->whereNotIn('status', [ParcelStatusEnum::DONE, ParcelStatusEnum::CANCELED])
            ->with(['source', 'destination', 'business', 'deliveryCourier'])
            ->firstOrFail();

        throw_if($parcel->isNotAccepted(), ParcelIsNotAcceptedException::class);

        throw_if($parcel->deliveryCourier->hasAnyIsOnGoingParcel(), DeliveryCourierHasOneOnGoingParcelException::class);

        $parcel->update(['status' => ParcelStatusEnum::IS_GOING_TO_SOURCE]);

        return $parcel;
    }


    /**
     * @throws ParcelIsNotOnGoingToSourceStatusException|Throwable
     */
    public function goToDestination(string $parcelUuid, int $deliveryId,): Parcel
    {
        $parcel = Parcel::query()
            ->whereUuid($parcelUuid)
            ->whereDeliveryCourierId($deliveryId)
            ->with(['source', 'destination', 'business', 'deliveryCourier'])
            ->whereNotIn('status', [ParcelStatusEnum::DONE, ParcelStatusEnum::CANCELED])
            ->firstOrFail();

        throw_unless($parcel->isInGoingToSource(), ParcelIsNotOnGoingToSourceStatusException::class);

        $parcel->update(['status' => ParcelStatusEnum::IS_GOING_TO_DESTINATION]);

        return $parcel;
    }

    /**
     * @throws Throwable
     */
    public function deliverParcel(string $parcelUuid, int $deliveryId): Parcel
    {
        $parcel = Parcel::query()
            ->whereUuid($parcelUuid)
            ->whereDeliveryCourierId($deliveryId)
            ->whereNotIn('status', [ParcelStatusEnum::DONE, ParcelStatusEnum::CANCELED])
            ->with(['source', 'destination', 'business', 'deliveryCourier'])
            ->firstOrFail();

        throw_unless($parcel->isInGoingToDestination(), ParcelIsNotOnGoingToDestinationStatusException::class);

        $parcel->update(['status' => ParcelStatusEnum::DONE->value]);

        return $parcel;
    }


    /**
     * @throws Exception
     */
    public function assignParcel(int $deliveryId, string $uuid): Parcel
    {
        try {
            DB::beginTransaction();

            $parcel = Parcel::query()
                ->whereNull('delivery_courier_id')
                ->whereStatus(ParcelStatusEnum::PENDING)
                ->whereUuid($uuid)
                ->with(['source', 'destination', 'business', 'deliveryCourier'])
                ->lockForUpdate()
                ->firstOrFail();

            $parcel->update(['delivery_courier_id' => $deliveryId, 'status' => ParcelStatusEnum::ACCEPTED]);

            DB::commit();
            return $parcel;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::critical('parcel assigment failed.', [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'parcel_uuid' => $uuid,
            ]);
            throw new $exception();
        }
    }
}
