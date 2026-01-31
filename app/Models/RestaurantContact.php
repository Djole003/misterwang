<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantContact extends Model
{
    protected $fillable = [
        'restaurant_id',
        'address',
        'phone',
        'email',
        'working_hours',
    ];

    // ✅ VEZA KA RESTORANU
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
