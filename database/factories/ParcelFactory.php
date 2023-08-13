<?php

namespace Database\Factories;

use App\Enums\ParcelStatusEnum;
use App\Models\Business;
use App\Models\DeliveryCourier;
use App\Models\Parcel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Parcel>
 */
class ParcelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'delivery_courier_id' => DeliveryCourier::factory(),
            'business_id' => Business::factory(),
            'status' => $this->faker->randomElement(ParcelStatusEnum::values())
        ];
    }
}
