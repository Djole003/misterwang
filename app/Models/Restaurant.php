<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function products()
    {
        return $this->belongsToMany(Product::class, 'restaurant_product_status')
            ->withPivot('is_available', 'price_delivery', 'price_takeaway')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    public function isOpen()
    {
        // ADMIN ima glavnu kontrolu
        if (!$this->is_active) {
            return false;
        }

        // ako je aktivan → radi
        return true;
    }
}
