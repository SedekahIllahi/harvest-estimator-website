<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $table = 'lands';

    protected $fillable = [
        'user_id',
        'nickname',
        'area_size',
        'lat',
        'lng',
    ];

    protected $casts = [
        'area_size' => 'decimal:2',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    /**
     * Relasi ke User (pemilik lahan)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Ubinan (hasil panen)
     */
    public function ubinans()
    {
        return $this->hasMany(Ubinan::class);
    }

    /**
     * Cek apakah lahan dimiliki oleh user tertentu
     */
    public function isOwnedBy(User $user)
    {
        return $this->user_id === $user->id;
    }

    /**
     * Accessor untuk luas dalam hektar (karena sudah disimpan dalam hektar, hanya untuk konsistensi)
     */
    public function getAreaInHectareAttribute()
    {
        return $this->area_size;
    }

    /**
     * Mendapatkan koordinat dalam format array
     */
    public function getCoordinatesAttribute()
    {
        if ($this->lat && $this->lng) {
            return ['lat' => (float) $this->lat, 'lng' => (float) $this->lng];
        }
        return null;
    }
}