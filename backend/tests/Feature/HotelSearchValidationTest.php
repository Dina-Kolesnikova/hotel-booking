<?php

test('search with all require fields missing returns 422 and error for each missing value', function () {
    $response = $this->getJson('/api/hotels');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['city']);
    $response->assertJsonValidationErrors(['check_in']);
    $response->assertJsonValidationErrors(['check_out']);
    $response->assertJsonValidationErrors(['guests']);
});

test('search with checkin equal checkout returns 422 and error that checkout should be later than checkin', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-10&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['check_out']);
});
