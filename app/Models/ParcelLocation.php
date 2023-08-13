<?php

namespace App\Models;

use App\Enums\ParcelLocationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParcelLocation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ParcelLocationTypeEnum::class
    ];

    public $timestamps = false;

    public function parcel(): BelongsTo
    {
        return $this->belongsTo(Parcel::class);
    }
}
