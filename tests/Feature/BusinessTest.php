<?php

namespace Tests\Feature;

use App\Enums\ParcelStatusEnum;
use App\Models\Business;
use App\Models\Parcel;
use App\Models\ParcelLocation;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    public function testDeliveryCourierCannotCreateParcel(): void
    {
        $business = Business::factory()->create();

        Sanctum::actingAs($business, ['delivery']);

        $response = $this->postJson(route('v1.business.parcels.store'));

        $response->assertStatus(403);
    }

    public function testBusinessCanCreateParcel(): void
    {
        $business = Business::factory()->create();

        Sanctum::actingAs($business, ['business']);

        $response = $this->postJson(route('v1.business.parcels.store'), [
            'source_name' => $this->faker->name,
            'source_address' => $this->faker->city,
            'source_phone' => $this->faker->numerify('###########'),
            'source_latitude' => $this->faker->latitude,
            'source_longitude' => $this->faker->longitude,
            'destination_name' => $this->faker->name,
            'destination_address' => $this->faker->city,
            'destination_phone' => $this->faker->numerify('###########'),
            'destination_latitude' => $this->faker->latitude,
            'destination_longitude' => $this->faker->longitude,
        ]);

        $response->assertStatus(201);
        $this->assertArrayHasKey('parcel_uuid', $response->json());
        $this->assertDatabaseCount(ParcelLocation::class, 2);
    }


    public function testBusinessCanCancelParcel(): void
    {
        $business = Business::factory()->create();

        Sanctum::actingAs($business, ['business']);

        $parcel = Parcel::factory()->state([
            'delivery_courier_id' => null,
            'business_id' => $business->id,
            'status' => ParcelStatusEnum::ACCEPTED->value
        ])->create();

        $response = $this->patchJson(route('v1.business.parcels.cancel', $parcel->uuid));
        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('uuid', $response->json());
        $this->assertSame(ParcelStatusEnum::CANCELED->value, $parcel->refresh()->status->value);
        $this->assertSame($response->json('uuid'), $parcel->refresh()->uuid);
    }
}
