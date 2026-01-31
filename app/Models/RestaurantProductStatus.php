<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantProductStatus extends Model
{
    protected $table = 'restaurant_product_status';

    protected $fillable = [
        'restaurant_id',
        'product_id',
        'is_available',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIJE
    |--------------------------------------------------------------------------
    */

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
