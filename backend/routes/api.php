<?php

use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;

Route::get('/hotels', [HotelController::class, 'index']);

Route::get('/hotels/{id}', function ($id) {
    return Hotel::findOrFail($id);
});

Route::get('/hotels/{id}/room-types', function ($id) {
    return Hotel::findOrFail($id)->roomTypes;
});

Route::get('/room-types/{id}', function ($id) {
    return RoomType::findOrFail($id);
});

Route::get('/room-types/{id}/nightly-inventories', function ($id) {
    return RoomType::findOrFail($id)->nightlyInventories;
});
