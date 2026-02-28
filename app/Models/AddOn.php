<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category; // 👈 dodaj ovo

class AddOn extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_add_on');
    }

    // 👇 DODAJ OVO
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_add_on');
    }
}