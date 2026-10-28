<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function nightlyInventories()
    {
        return $this->hasMany(NightlyInventory::class);
    }
}
