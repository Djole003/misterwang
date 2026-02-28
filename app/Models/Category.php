<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AddOn;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    /**
     * Veza sa proizvodima
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * VEZA SA DODACIMA (NOVO)
     */
    public function addOns()
    {
        return $this->belongsToMany(AddOn::class, 'category_add_on');
    }
}