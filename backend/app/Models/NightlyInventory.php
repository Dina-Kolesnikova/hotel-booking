<?php

namespace App\Models;

use Database\Factories\NightlyInventoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NightlyInventory extends Model
{
    /** @use HasFactory<NightlyInventoryFactory> */
    use HasFactory;

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
