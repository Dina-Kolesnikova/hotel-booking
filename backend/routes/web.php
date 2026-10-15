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
        ['name' => 'Kyiv Inn', 'city' => 'Kyiv'],
        ['name' => 'Lviv Hotel', 'city' => 'Lviv']
    ];
});
