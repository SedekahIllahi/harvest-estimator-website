<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ubinan extends Model
{
    protected $guarded = [];

    public function land()
    {
        return $this->belongsTo(Land::class);
    }
}