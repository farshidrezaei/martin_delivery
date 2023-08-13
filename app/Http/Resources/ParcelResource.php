<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->uuid,
            'status' => $this->resource->status->value,
            'delivery' => DeliveryCourierResource::make($this->whenLoaded('deliveryCourier')),
            'business' => BusinessResource::make($this->whenLoaded('business')),
            'source' => ParcelLocationResource::make($this->whenLoaded('source')),
            'destination' => ParcelLocationResource::make($this->whenLoaded('destination')),
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
