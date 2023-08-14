<?php

namespace App\Models;

use App\Enums\ParcelStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Parcel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => ParcelStatusEnum::class
    ];


    public function locations(): HasMany
    {
        return $this->hasMany(ParcelLocation::class);
    }

    public function source(): HasOne
    {
        return $this->hasOne(ParcelLocation::class)->whereType('source');
    }

    public function destination(): HasOne
    {
        return $this->hasOne(ParcelLocation::class)->whereType('destination');
    }

    public function trackLogs(): HasMany
    {
        return $this->hasMany(ParcelTrackLog::class);
    }

    public function lastLocation(): HasOne
    {
        return $this->hasOne(ParcelTrackLog::class)->latestOfMany('created_at', 'trackLogs');
    }

    public function deliveryCourier(): BelongsTo
    {
        return $this->belongsTo(DeliveryCourier::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function isCancelable(): bool
    {
        return in_array($this->status->value, [ParcelStatusEnum::PENDING->value, ParcelStatusEnum::ACCEPTED->value]);
    }

    public function isAccepted(): bool
    {
        return $this->status->value === ParcelStatusEnum::ACCEPTED->value;
    }

    public function isInGoingToSource(): bool
    {
        return $this->status->value === ParcelStatusEnum::IS_GOING_TO_SOURCE->value;
    }

    public function isInGoingToDestination(): bool
    {
        return $this->status->value === ParcelStatusEnum::IS_GOING_TO_DESTINATION->value;
    }

    public function isNotAccepted(): bool
    {
        return !$this->isAccepted();
    }


}
