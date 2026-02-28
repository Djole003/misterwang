<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'polygon',
        'price',
        'minimum_amount',
    ];

    protected $casts = [
        'polygon' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
