<?php

namespace Database\Factories;

use App\Models\DeliveryCourier;
use App\Models\Parcel;
use App\Models\ParcelLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ParcelLocation>
 */
class ParcelTrackLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parcel_id' => Parcel::factory(),
            'delivery_courier_id' => DeliveryCourier::factory(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}
