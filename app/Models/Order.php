<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AddOn; // ⬅⬅⬅ OVO JE FALILO

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
     * Računa ukupnu cenu porudžbine
     */
    public function calculateTotalPrice(): float
    {
        // učitaj relacije ako nisu već
        $this->loadMissing('orderProducts.product');

        $total = 0;
        $orderType = $this->order_type ?? 'delivery';

        foreach ($this->orderProducts as $item) {
            if (!$item->product) continue;

            // osnovna cena
            $price = $orderType === 'delivery'
                ? $item->product->price_delivery
                : $item->product->price_takeaway;

            $details = $item->details ?? [];

            // veličina
            if (($details['size'] ?? null) === 'velika') {
                $price += 200;
            }

            // dodaci
            if (!empty($details['addons'])) {
                $addonsTotal = AddOn::whereIn('id', $details['addons'])
                    ->sum('price');
                $price += $addonsTotal;
            }

            $total += $price * $item->quantity;
        }

        // dostava
        if ($this->order_type === 'delivery') {
            $total += $this->delivery_price ?? 0;
        }

        return $total;
    }
}
