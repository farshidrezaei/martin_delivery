<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParcelTrackLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const UPDATED_AT = null;


    public function parcel(): BelongsTo
    {
        return $this->belongsTo(Parcel::class);
    }
}
