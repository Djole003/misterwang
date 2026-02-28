<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RestaurantProductStatus;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price_delivery',
        'price_takeaway',
        'image_path',
        'category_id',
        'has_size',
        'has_sos',
        'has_meat',
        'has_rice_option',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR ZA CENU (PO LOKALU + POPUST)
    |--------------------------------------------------------------------------
    */
    public function getPriceAttribute()
    {
        $type = session('order_type', 'delivery');
        $restaurantId = session('restaurant_id');

        // Ako je proizvod učitan preko relacije restorana (pivot postoji)
        if ($this->pivot && isset($this->pivot->price_delivery)) {

            $basePrice = $type === 'takeaway'
                ? $this->pivot->price_takeaway
                : $this->pivot->price_delivery;

        } else {
            // fallback ako nije učitan preko restorana
            $basePrice = $type === 'takeaway'
                ? $this->price_takeaway
                : $this->price_delivery;
        }

        // --- LOGIKA POPUSTA ---
        $isPice = false;

        if ($this->relationLoaded('category')) {
            $isPice = ($this->category->slug === 'pice');
        } elseif ($this->category) {
            $isPice = ($this->category->slug === 'pice');
        }

        // 15% popust osim za piće
        if (!$isPice) {
            $basePrice = round($basePrice * 0.85);
        }

        return $basePrice;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

    public function addOns()
    {
        return $this->belongsToMany(AddOn::class, 'product_add_on');
    }

    public function restaurantStatuses()
    {
        return $this->hasMany(
            RestaurantProductStatus::class,
            'product_id'
        );
    }

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_product_status')
            ->withPivot('is_available', 'price_delivery', 'price_takeaway')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | DOSTUPNOST PROIZVODA PO LOKALU
    |--------------------------------------------------------------------------
    */
    public function isAvailableForCurrentRestaurant(): bool
    {
        $restaurantId = session('restaurant_id');

        if (!$restaurantId) {
            return true;
        }

        $status = RestaurantProductStatus::where('product_id', $this->id)
            ->where('restaurant_id', $restaurantId)
            ->first();

        if (!$status) {
            return true;
        }

        return (bool) $status->is_available;
    }
}
