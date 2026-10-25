<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ping', function () {
    return ['status' => 'ok', 'time' => now()];
});

Route::get('/hotels', function () {
    return App\Models\Hotel::all();
});
