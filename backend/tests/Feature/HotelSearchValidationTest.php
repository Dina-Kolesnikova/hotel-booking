<?php
test('H1: search with all valid fields no errors should happen', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-11&guests=1');

    $response->assertJsonMissingValidationErrors(['city']);
    $response->assertJsonMissingValidationErrors(['check_in']);
    $response->assertJsonMissingValidationErrors(['check_out']);
    $response->assertJsonMissingValidationErrors(['guests']);
});

test('V1: search with all require fields missing returns 422 and error for each missing value', function () {
    $response = $this->getJson('/api/hotels');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['city']);
    $response->assertJsonValidationErrors(['check_in']);
    $response->assertJsonValidationErrors(['check_out']);
    $response->assertJsonValidationErrors(['guests']);
});

test('D3: search with checkin equal checkout returns 422 and error that checkout should be later than checkin', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-10&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['check_out']);
});

test('V6: search with 0 guests returns 422 and error that min guests should be 1', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-27&check_out=2030-01-28&guests=0');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['guests']);
});

test('D1: search with checkin yesterday (or in the past) returns 422 and error that checkin should be today or later', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in='.now()->subDay()->toDateString().'&check_out='.now()->toDateString().'&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['check_in']);
    $response->assertJsonMissingValidationErrors(['check_out']);
});

test('V4: search with city longer 100 characters 422 and error that city name max allowed 100 chars', function () {
    $response = $this->getJson('/api/hotels?city='.str_repeat('a', 101).'&check_in=2030-01-10&check_out=2030-01-11&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['city']);
});
test('V2: search with empty city returns 422 and error for city', function () {
    $response = $this->getJson('/api/hotels?city=&check_in=2030-01-10&check_out=2030-01-11&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['city']);
});

test('V3: search with city of exactly 100 characters has no city error', function () {
    $response = $this->getJson('/api/hotels?city='.str_repeat('a', 100).'&check_in=2030-01-10&check_out=2030-01-11&guests=1');

    $response->assertJsonMissingValidationErrors(['city']);
});

test('V5: search with non-latin city name has no city error', function () {
    $response = $this->getJson('/api/hotels?city='.urlencode('Київ').'&check_in=2030-01-10&check_out=2030-01-11&guests=1');

    $response->assertJsonMissingValidationErrors(['city']);
});

test('V7a: search with non-numeric guests returns 422 and error for guests', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-11&guests=abc');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['guests']);
});

test('V7b: search with negative guests returns 422 and error for guests', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-11&guests=-1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['guests']);
});

test('V7c: search with decimal guests returns 422 and error for guests', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-11&guests=1.5');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['guests']);
});

test('V8: search with minimum guests has no guests error', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-11&guests=1');

    $response->assertJsonMissingValidationErrors(['guests']);
});

test('D2: search with check_in today has no check_in error', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in='.now()->toDateString().'&check_out='.now()->addDay()->toDateString().'&guests=1');

    $response->assertJsonMissingValidationErrors(['check_in']);
});

test('D4: search with check_out before check_in returns 422 and error for check_out', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-01-09&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['check_out']);
});

test('D5a: search with non-date check_in returns 422 and error for check_in', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=abc&check_out=2030-01-11&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['check_in']);
});

test('D5b: search with impossible check_out date returns 422 and error for check_out', function () {
    $response = $this->getJson('/api/hotels?city=Paris&check_in=2030-01-10&check_out=2030-13-45&guests=1');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['check_out']);
});
