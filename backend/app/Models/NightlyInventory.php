<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NightlyInventory extends Model
{
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
