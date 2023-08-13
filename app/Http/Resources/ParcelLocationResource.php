<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'address' => $this->resource->address,
            'latitude' => $this->resource->latitude,
            'longitude' => $this->resource->longitude
        ];
    }
}
