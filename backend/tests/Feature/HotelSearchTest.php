<?php

use App\Models\Hotel;
use App\Models\NightlyInventory;
use App\Models\RoomType;

// TODO: enable RefreshDatabase once tests run on a MySQL test database (the hotels migration CHECK fails on SQLite).
// uses(RefreshDatabase::class);

test('S1: search with valid params returns the available hotel with total price', function () {
    $hotel = Hotel::factory()->create(['city' => 'Paris']);
    $roomType = RoomType::factory()->create(['hotel_id' => $hotel->id, 'capacity' => 2]);
    NightlyInventory::factory()->create(['room_type_id' => $roomType->id, 'date' => '2030-01-10', 'price' => 10000]);
    NightlyInventory::factory()->create(['room_type_id' => $roomType->id, 'date' => '2030-01-11', 'price' => 12000]);

    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-12&guests=1');

    $response->assertStatus(200);
    $response->assertJsonCount(1);
    $response->assertJsonPath('0.hotel_id', $hotel->id);
    $response->assertJsonPath('0.total_price', 22000);
})->skip('Needs a MySQL test database: the hotels migration CHECK does not run on SQLite (see docs/qa/search/test-cases.md)');
