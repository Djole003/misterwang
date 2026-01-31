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
