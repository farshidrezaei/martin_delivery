<?php

namespace App\Models;

use App\Enums\ParcelStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class DeliveryCourier extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = ['id'];

    public function parcels(): HasMany
    {
        return $this->hasMany(Parcel::class);
    }

    public function hasAnyIsOnGoingParcel(): bool
    {
        return $this->parcels()
            ->whereIn('status', [
                ParcelStatusEnum::IS_GOING_TO_SOURCE->value,
                ParcelStatusEnum::IS_GOING_TO_DESTINATION->value
            ])
            ->exists();
    }
}
