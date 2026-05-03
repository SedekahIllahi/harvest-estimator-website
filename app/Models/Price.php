<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity',
        'slug',
        'price_per_kg',
        'conversion_factor',
    ];

    // Helper untuk mengambil faktor konversi berdasarkan slug
    public static function getFactor($slug)
    {
        $price = self::where('slug', $slug)->first();
        return $price ? $price->conversion_factor : 4.2;
    }

    // Helper untuk mengambil harga per kg
    public static function getPrice($slug)
    {
        $price = self::where('slug', $slug)->first();
        return $price ? $price->price_per_kg : 5500;
    }
}