<?php

namespace App\Http\Controllers\API\V1\Delivery;

use App\Enums\ParcelStatusEnum;
use App\Exceptions\ParcelIsNotAcceptedException;
use App\Facades\DeliveryFacade;
use App\Facades\ParcelTrackLogFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\ParcelLocationRequest;
use App\Http\Resources\ParcelResource;
use App\Notifications\BusinessParcelNotification;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class DeliveryCourierParcelController extends Controller
{

    public function pendingParcels(PaginationRequest $request): AnonymousResourceCollection
    {
        $parcels = DeliveryFacade::getPendingParcels($request->validated('page'), $request->validated('per_page'));

        return ParcelResource::collection($parcels);
    }

    public function acceptedParcels(PaginationRequest $request): AnonymousResourceCollection
    {
        $parcels = DeliveryFacade::getDeliveryCourierParcels(
            Auth::id(),
            $request->validated('page'),
            $request->validated('per_page'),
            ParcelStatusEnum::ACCEPTED
        );

        return ParcelResource::collection($parcels);
    }


    /**
     * @throws Exception
     */
    public function assignParcel(ParcelLocationRequest $request, string $uuid): ParcelResource
    {
        $parcel = DeliveryFacade::assignParcel(Auth::id(), $uuid);

        ParcelTrackLogFacade::store($parcel->id, $request->validated('latitude'), $request->validated('longitude'));

        $parcel->business->notify(new BusinessParcelNotification($parcel));

        return ParcelResource::make($parcel);
    }


    /**
     * @throws ParcelIsNotAcceptedException
     * @throws Throwable
     */
    public function goToParcelSource(ParcelLocationRequest $request, string $uuid): ParcelResource
    {
        $parcel = DeliveryFacade::goToSource($uuid, Auth::id());

        ParcelTrackLogFacade::store($parcel->id, $request->validated('latitude'), $request->validated('longitude'));

        $parcel->business->notify(new BusinessParcelNotification($parcel));

        return ParcelResource::make($parcel);
    }

    /**
     * @throws Throwable
     */
    public function goToParcelDestination(ParcelLocationRequest $request, string $uuid): ParcelResource
    {
        $parcel = DeliveryFacade::goToDestination($uuid, Auth::id());

        ParcelTrackLogFacade::store($parcel->id, $request->validated('latitude'), $request->validated('longitude'));

        $parcel->business->notify(new BusinessParcelNotification($parcel));

        return ParcelResource::make($parcel);
    }

    /**
     * @throws Throwable
     */
    public function deliverParcel(ParcelLocationRequest $request, string $uuid): ParcelResource
    {
        $parcel = DeliveryFacade::deliverParcel($uuid, Auth::id());

        ParcelTrackLogFacade::store($parcel->id, $request->validated('latitude'), $request->validated('longitude'));

        $parcel->business->notify(new BusinessParcelNotification($parcel));

        return ParcelResource::make($parcel);
    }
}
