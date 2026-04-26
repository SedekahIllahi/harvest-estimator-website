<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubinan extends Model
{
    use HasFactory;

    protected $table = 'ubinans';

    protected $fillable = [
        'land_id',
        'sample_weight_kg',
        'estimated_yield_tons',
        'weather_note',
    ];

    /**
     * Relasi ke lahan.
     */
    public function land()
    {
        return $this->belongsTo(Land::class);
    }

    /**
     * Akses user melalui lahan (pemilik lahan).
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Land::class, 'id', 'id', 'land_id', 'user_id');
    }
}