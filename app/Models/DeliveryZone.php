<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'center_lat',
        'center_lng',
        'radius',
        'price',
        'minimum_amount',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
