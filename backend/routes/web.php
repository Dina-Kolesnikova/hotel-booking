<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ping', function () {
    return ['status' => 'ok', 'time' => now()];
});

Route::get('/hotels', function () {
    return [
        ['name' => 'Kyiv Inn', 'city' => 'Kyiv', 'price_per_night' => 4500],
        ['name' => 'Lviv Hotel', 'city' => 'Lviv', 'price_per_night' => 3800]
    ];
});
