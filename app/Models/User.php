<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',   
        'password',            
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'pin',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pin' => 'hashed', // otomatis hash PIN saat disimpan
        ];
    }

    /**
     * Relasi ke lahan (lands)
     */
    public function lands()
    {
        return $this->hasMany(Land::class);
    }

    /**
     * Relasi ke ubinan (melalui lahan? langsung? Tergantung skema, bisa ditambahkan)
     * Jika ingin akses langsung: $user->ubinans()
     */
    public function ubinans()
    {
        return $this->hasManyThrough(Ubinan::class, Land::class);
    }

    /**
     * Cek apakah user adalah petani (farmer)
     */
    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    /**
     * Cek apakah user adalah bapak dukuh (admin desa)
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}