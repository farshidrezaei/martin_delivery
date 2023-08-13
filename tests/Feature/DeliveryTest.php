<?php

namespace Tests\Feature;

use App\Enums\ParcelLocationTypeEnum;
use App\Enums\ParcelStatusEnum;
use App\Http\Resources\ParcelResource;
use App\Models\DeliveryCourier;
use App\Models\Parcel;
use App\Models\ParcelLocation;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeliveryTest extends TestCase
{
    public function testDeliveryCanSeeListOfPendingParcels(): void
    {
        $deliveryCourier = DeliveryCourier::factory()->create();

        Sanctum::actingAs($deliveryCourier, ['delivery']);

        $parcel = Parcel::factory()->state([
            'delivery_courier_id' => null,
            'status' => ParcelStatusEnum::PENDING->value
        ])->create();

        Parcel::factory()->state([
            'delivery_courier_id' => $deliveryCourier->id,
            'status' => ParcelStatusEnum::ACCEPTED->value
        ])->create();

        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel)->create();

        $response = $this->getJson(route('v1.delivery.parcel.pending'));

        $response->assertStatus(200);
        $this->assertResponseContainsParcels($response, $parcel);
        $this->assertCount(1, $response->json('data'));
    }

    public function testDeliveryCanSeeListOfOwnAcceptedParcels(): void
    {
        $deliveryCourier = DeliveryCourier::factory()->create();
        $deliveryCourier2 = DeliveryCourier::factory()->create();

        Sanctum::actingAs($deliveryCourier, ['delivery']);

        $parcel = Parcel::factory()->state([
            'delivery_courier_id' => $deliveryCourier->id,
            'status' => ParcelStatusEnum::ACCEPTED->value
        ])->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel)->create();

        $parcel2 = Parcel::factory()->state([
            'delivery_courier_id' => null,
            'status' => ParcelStatusEnum::PENDING->value
        ])->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel2)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel2)->create();

        $parcel3 = Parcel::factory()->state([
            'delivery_courier_id' => $deliveryCourier2->id,
            'status' => ParcelStatusEnum::ACCEPTED->value
        ])->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel3)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel3)->create();

        $response = $this->getJson(route('v1.delivery.parcel.accepted'));

        $response->assertStatus(200);
        $this->assertResponseContainsParcels($response, $parcel);
        $this->assertCount(1, $response->json('data'));
    }

    public function testDeliveryCanChangePendingParcelToAccept()
    {
        $deliveryCourier = DeliveryCourier::factory()->create();

        Sanctum::actingAs($deliveryCourier, ['delivery']);

        $parcel = Parcel::factory()->state([
            'delivery_courier_id' => null,
            'status' => ParcelStatusEnum::PENDING->value
        ])->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel)->create();

        $response = $this->patchJson(route('v1.delivery.parcel.assign', $parcel->uuid));
        $response->assertStatus(200);
        $this->assertSame(ParcelStatusEnum::ACCEPTED->value, $parcel->refresh()->status->value);
        $this->assertSame($deliveryCourier->id, $parcel->refresh()->delivery_courier_id);
        Notification::assertCount(1);
    }

    public function testDeliveryCanChangeAcceptedParcelToGoingToSourceLocation()
    {
        $deliveryCourier = DeliveryCourier::factory()->create();

        Sanctum::actingAs($deliveryCourier, ['delivery']);

        $parcel = Parcel::factory()->state([
            'delivery_courier_id' => $deliveryCourier->id,
            'status' => ParcelStatusEnum::ACCEPTED->value
        ])->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel)->create();

        $response = $this->patchJson(route('v1.delivery.parcel.go-to-parcel-source', $parcel->uuid));

        $response->assertStatus(200);
        $this->assertSame(ParcelStatusEnum::IS_GOING_TO_SOURCE->value, $parcel->refresh()->status->value);
        Notification::assertCount(1);
    }

    public function testDeliveryCanChangeGoingToSourceLocationParcelToGoingToDestinationLocation()
    {
        $deliveryCourier = DeliveryCourier::factory()->create();

        Sanctum::actingAs($deliveryCourier, ['delivery']);

        $parcel = Parcel::factory()->state([
            'delivery_courier_id' => $deliveryCourier->id,
            'status' => ParcelStatusEnum::IS_GOING_TO_SOURCE->value
        ])->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel)->create();

        $response = $this->patchJson(route('v1.delivery.parcel.go-to-destination', $parcel->uuid));


        $response->assertStatus(200);
        $this->assertSame(ParcelStatusEnum::IS_GOING_TO_DESTINATION->value, $parcel->refresh()->status->value);
        Notification::assertCount(1);
    }

    public function testDeliveryCanChangeGoingToDestinationLocationParcelToDone()
    {
        $deliveryCourier = DeliveryCourier::factory()->create();

        Sanctum::actingAs($deliveryCourier, ['delivery']);

        $parcel = Parcel::factory()->state([
            'delivery_courier_id' => $deliveryCourier->id,
            'status' => ParcelStatusEnum::IS_GOING_TO_DESTINATION->value
        ])->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::SOURCE])->for($parcel)->create();
        ParcelLocation::factory()->state(['type' => ParcelLocationTypeEnum::DESTINATION])->for($parcel)->create();

        $response = $this->patchJson(route('v1.delivery.parcel.deliver', $parcel->uuid));


        $response->assertStatus(200);
        $this->assertSame(ParcelStatusEnum::DONE->value, $parcel->refresh()->status->value);
        Notification::assertCount(1);
    }

    private function assertResponseContainsParcels(TestResponse $response, ...$parcels): void
    {
        $response->assertJson([
            'data' => array_map(function (Parcel $parcel) {
                return $this->ParcelToResourceResponse($parcel);
            }, $parcels),
        ]);
    }

    private function ParcelToResourceResponse(Parcel $parcel)
    {
        return json_decode(ParcelResource::make($parcel)->toJson(), true);
    }
}
