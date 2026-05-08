<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityPrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
        'effective_date' => 'datetime',
    ];
}