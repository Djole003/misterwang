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
    ];

    /**
     * Accessor za cenu u zavisnosti od tipa porudžbine
     */
    public function getPriceAttribute()
    {
        $type = session('order_type', 'delivery');

        $basePrice = $type === 'takeaway'
            ? $this->price_takeaway
            : $this->price_delivery;

        // --- LOGIKA POPUSTA ---
        $isPice = false;

        if ($this->relationLoaded('category')) {
            $isPice = ($this->category->slug === 'pice');
        } elseif ($this->category) {
            $isPice = ($this->category->slug === 'pice');
        }

        if (!$isPice) {
            $basePrice = round($basePrice * 0.85);
        }

        return $basePrice;
    }


    /**
     * Veza sa kategorijom
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Veza sa porudžbinama (many-to-many)
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity');
    }

    /**
     * Veza sa order_product (jedan-na-više)
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

    /**
     * Veza sa dodacima
     */
    public function addOns()
    {
        return $this->belongsToMany(AddOn::class, 'product_add_on');
    }

    /**
     * Veza sa statusima proizvoda po lokalu
     */
    public function restaurantStatuses()
    {
        return $this->hasMany(
            RestaurantProductStatus::class,
            'product_id'
        );
    }

    /**
     * ===============================
     * DOSTUPNOST PROIZVODA PO LOKALU
     * ===============================
     */
    public function isAvailableForCurrentRestaurant(): bool
    {
        $restaurantId = session('restaurant_id');

        // Ako nema izabranog lokala → dostupno
        if (!$restaurantId) {
            return true;
        }

        $status = RestaurantProductStatus::where('product_id', $this->id)
            ->where('restaurant_id', $restaurantId)
            ->first();

        // Ako nema zapisa → PODRAZUMEVANO dostupno
        if (!$status) {
            return true;
        }

        return (bool) $status->is_available;
    }
}
