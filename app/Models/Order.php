<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AddOn;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'status',
        'total_price',
        'delivery_info',
        'order_type',
        'delivery_zone',
        'delivery_price',
        'cutlery',
    ];

    protected $casts = [
        'delivery_info' => 'array',
    ];

    /* ================= RELACIJE ================= */

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ================= LOGIKA ================= */

    /**
     * Računa ukupnu cenu porudžbine – SA AKCIJSKOM CENOM
     */
    public function calculateTotalPrice(): float
    {
        $this->loadMissing('orderProducts.product');

        $total = 0;

        foreach ($this->orderProducts as $item) {

            if (!$item->product) {
                continue;
            }

            // KORISTIMO AKCIJSKU CENU PREKO ACCESSORA
            $price = $item->product->price;

            $details = $item->details ?? [];

            // Velika porcija
            if (($details['size'] ?? null) === 'velika') {
                $price += 200;
            }

            // Dodaci
            if (!empty($details['addons'])) {
                $addonsTotal = AddOn::whereIn('id', $details['addons'])
                    ->sum('price');

                $price += $addonsTotal;
            }

            $total += $price * $item->quantity;
        }

        // Dodavanje cene dostave
        if ($this->order_type === 'delivery') {
            $total += $this->delivery_price ?? 0;
        }

        return $total;
    }
}
