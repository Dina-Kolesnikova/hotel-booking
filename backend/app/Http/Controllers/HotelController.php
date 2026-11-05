<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city');

        return Hotel::where('city', $city)->get();
    }
}
