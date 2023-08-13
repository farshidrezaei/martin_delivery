<?php

namespace Database\Factories;

use App\Enums\ParcelLocationTypeEnum;
use App\Models\Parcel;
use App\Models\ParcelLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ParcelLocation>
 */
class ParcelLocationFactory extends Factory
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
            'type' => $this->faker->unique()->randomElement(ParcelLocationTypeEnum::values()),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'lat' => $this->faker->latitude(),
            'long' => $this->faker->longitude()
        ];
    }
}
