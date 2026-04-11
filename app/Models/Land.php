<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Add this import at the top

class Land extends Model
{
    use HasFactory; // 2. Add this trait inside the class

    // Protect against mass-assignment vulnerabilities
    protected $guarded = []; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ubinans()
    {
        return $this->hasMany(Ubinan::class);
    }
}