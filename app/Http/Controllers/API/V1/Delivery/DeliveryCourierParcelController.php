<?php

namespace App\Http\Controllers\API\V1\Delivery;

use App\Enums\ParcelStatusEnum;
use App\Exceptions\ParcelIsNotAcceptedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\ParcelResource;
use App\Services\DeliveryCourierService\DeliveryCourierService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class DeliveryCourierParcelController extends Controller
{
    public function __construct(private readonly DeliveryCourierService $deliveryCourierService)
    {
    }

    public function pendingParcels(PaginationRequest $request): AnonymousResourceCollection
    {
        $parcels = $this->deliveryCourierService
            ->getPendingParcels($request->validated('page'), $request->validated('per_page'));

        return ParcelResource::collection($parcels);
    }

    public function acceptedParcels(PaginationRequest $request): AnonymousResourceCollection
    {
        $parcels = $this->deliveryCourierService->getDeliveryCourierParcels(
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
    public function assignParcel(string $uuid): ParcelResource
    {
        $parcel = $this->deliveryCourierService->assignParcel(Auth::id(), $uuid);

        return ParcelResource::make($parcel);
    }


    /**
     * @throws ParcelIsNotAcceptedException
     * @throws Throwable
     */
    public function goToParcelSource(string $uuid): ParcelResource
    {
        $parcel = $this->deliveryCourierService->goToSource($uuid, Auth::id());
        return ParcelResource::make($parcel);
    }

    /**
     * @throws Throwable
     */
    public function goToParcelDestination(string $uuid): ParcelResource
    {
        $parcel = $this->deliveryCourierService->goToDestination($uuid, Auth::id());
        return ParcelResource::make($parcel);
    }

    /**
     * @throws Throwable
     */
    public function deliverParcel(string $uuid): ParcelResource
    {
        $parcel = $this->deliveryCourierService->deliverParcel($uuid, Auth::id());
        return ParcelResource::make($parcel);
    }
}
