<?php

namespace App\Services;

use App\Models\ParcelTrackLog;

class ParcelTrackLogService
{
    public function store(int $parcelId, float $latitude, float $longitude): ParcelTrackLog
    {
        return ParcelTrackLog::create([
            'parcel_id' => $parcelId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
