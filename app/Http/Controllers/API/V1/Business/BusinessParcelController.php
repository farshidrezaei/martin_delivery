<?php

namespace App\Http\Controllers\API\V1\Business;

use App\DTOs\ParcelDTO;
use App\Exceptions\ParcelIsNotInCancelableException;
use App\Facades\BusinessFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessParcelRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class BusinessParcelController extends Controller
{

    /**
     * @throws Exception
     */
    public function store(BusinessParcelRequest $request, ParcelDTO $parcelDTO): JsonResponse
    {
        $parcelDTO->setBusinessId(Auth::id())
            ->setSourceName($request->validated('source_name'))
            ->setSourceAddress($request->validated('source_address'))
            ->setSourcePhone($request->validated('source_phone'))
            ->setSourceLat($request->validated('source_latitude'))
            ->setSourceLong($request->validated('source_longitude'))
            ->setDestinationName($request->validated('destination_name'))
            ->setDestinationAddress($request->validated('destination_address'))
            ->setDestinationPhone($request->validated('destination_phone'))
            ->setDestinationLat($request->validated('destination_latitude'))
            ->setDestinationLong($request->validated('destination_longitude'));

        $parcel = BusinessFacade::storeParcel($parcelDTO);

        return new JsonResponse(['parcel_uuid' => $parcel->uuid], 201);
    }


    /**
     * @throws Throwable
     * @throws ParcelIsNotInCancelableException
     */
    public function cancel(string $uuid): JsonResponse
    {
        $parcel = BusinessFacade::cancelParcel(Auth::id(), $uuid);
        return new  JsonResponse([
            'uuid' => $parcel->uuid,
            'status' => $parcel->status->value
        ]);
    }
}
