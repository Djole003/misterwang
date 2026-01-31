<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadnoVreme extends Model
{
    protected $table = 'radno_vreme';

    protected $fillable = [
        'dan',
        'otvara_se',
        'zatvara_se',
    ];

    public $timestamps = true;
}
