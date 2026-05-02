<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $table = 'lands';

    // We use guarded instead of fillable so we don't have to update this 
    // every single time we add a new column to the database.
    protected $guarded = [];

    // Merged everything into ONE clean casts array
    protected $casts = [
        'area_size' => 'decimal:2',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'boundaries' => 'array', // CRITICAL for the map polygons!
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