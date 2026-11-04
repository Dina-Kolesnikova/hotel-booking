<?php

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/hotels', function (Request $request) {
    $city = $request->query('city');
    return Hotel::where('city', $city)->get();
});

Route::get('/hotels/{id}', function ($id) {
    return Hotel::findOrFail($id);
});

Route::get('/hotels/{id}/room-types', function ($id) {
    return Hotel::findOrFail($id)->roomTypes;
});
