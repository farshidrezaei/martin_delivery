<?php

namespace App\Facades;

use App\Models\ParcelTrackLog;
use App\Services\ParcelTrackLogService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ParcelTrackLog store(int $parcelId, float $latitude, float $longitude);
 */
class ParcelTrackLogFacade extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return ParcelTrackLogService::class;
    }
}