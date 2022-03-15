<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(OrderItemOrbit::class, 'order_item_id', 'id');
    }
}
