<?php

namespace App\Models;

use Database\Factories\HotelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    /** @use HasFactory<HotelFactory> */
    use HasFactory;

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }
}
