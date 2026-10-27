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
Route::get('/room-types/{id}/hotel', function ($id) {
    return App\Models\RoomType::findOrFail($id)->hotel;
});
