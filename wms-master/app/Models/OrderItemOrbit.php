<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemOrbit extends Model
{
    use HasFactory;


    public function orbitStock()
    {
        return $this->hasOne(OrbitStock::class, 'id', 'orbit_stock_id');
    }
}
