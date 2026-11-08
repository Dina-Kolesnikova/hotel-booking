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

test('search with 0 guests returns 422 and error that min guests should be 1', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-27&check_out=2030-01-28&guests=0');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['guests']);
});

test('search with checkin yesterday (or in the past) returns 422 and error that checkin should be today or later', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in='.now()->subDay()->toDateString().'&check_out='.now()->toDateString().'&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['check_in']);
    $response->assertJsonMissingValidationErrors(['check_out']);
});