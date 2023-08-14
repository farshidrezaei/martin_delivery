<?php

namespace App\Services;

use App\Dtos\ParcelDTO;
use App\Enums\ParcelLocationTypeEnum;
use App\Enums\ParcelStatusEnum;
use App\Exceptions\ParcelIsNotInCancelableException;
use App\Models\Parcel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class BusinessService
{
    /**
     * @throws Exception
     */
    public function storeParcel(ParcelDTO $parcelDTO): Parcel
    {
        try {
            DB::beginTransaction();

            $parcel = Parcel::query()->create([
                'uuid' => Str::uuid(),
                'business_id' => $parcelDTO->getBusinessId(),
                'status' => ParcelStatusEnum::PENDING
            ]);

            $parcel->locations()->create([
                'type' => ParcelLocationTypeEnum::SOURCE,
                'name' => $parcelDTO->getSourceName(),
                'address' => $parcelDTO->getSourceAddress(),
                'phone' => $parcelDTO->getSourcePhone(),
                'latitude' => $parcelDTO->getSourceLat(),
                'longitude' => $parcelDTO->getSourceLong(),
            ]);

            $parcel->locations()->create([
                'type' => ParcelLocationTypeEnum::DESTINATION,
                'name' => $parcelDTO->getDestinationName(),
                'address' => $parcelDTO->getDestinationAddress(),
                'phone' => $parcelDTO->getDestinationPhone(),
                'latitude' => $parcelDTO->getDestinationLat(),
                'longitude' => $parcelDTO->getDestinationLong(),
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            Log::critical('business parcel creation failed.', [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'business_id' => $parcelDTO->getBusinessId(),
            ]);
            throw $exception;
        }

        return $parcel;
    }


    /**
     * @throws ParcelIsNotInCancelableException|Throwable
     */
    public function cancelParcel(int $businessId, string $parcelUuid): Parcel
    {
        $parcel = Parcel::query()->whereBusinessId($businessId)->whereUuid($parcelUuid)->firstOrFail();

        throw_if(!$parcel->isCancelable(), ParcelIsNotInCancelableException::class);

        $parcel->update([
            'status' => ParcelStatusEnum::CANCELED
        ]);

        return $parcel;
    }
}
