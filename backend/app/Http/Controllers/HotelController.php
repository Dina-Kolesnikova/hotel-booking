<?php

namespace App\Http\Controllers;

use App\Models\NightlyInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HotelController extends Controller
{
    /**
     * Room types in a city with a free room for every night in [check_in, check_out).
     */
    public function index(Request $request)
    {
        $city = $request->query('city');
        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $guests = (int) $request->query('guests');

        $nights = (int) Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));

        return NightlyInventory::query()
            ->join('room_types', 'room_types.id', '=', 'nightly_inventories.room_type_id')
            ->join('hotels', 'hotels.id', '=', 'room_types.hotel_id')
            ->where('hotels.city', $city)
            ->where('nightly_inventories.date', '>=', $checkIn)
            ->where('nightly_inventories.date', '<', $checkOut)
            ->where('nightly_inventories.rooms_left', '>', 0)
            ->where('room_types.capacity', '>=', $guests)
            ->groupBy('hotels.id', 'hotels.name', 'room_types.id', 'room_types.name', 'room_types.capacity')
            ->havingRaw('COUNT(*) = ?', [$nights])
            ->selectRaw('hotels.id AS hotel_id, hotels.name AS hotel_name, room_types.id AS room_type_id, room_types.name AS room_type_name, room_types.capacity, COUNT(*) AS nights, SUM(nightly_inventories.price) AS total_price')
            ->get();
    }
}
