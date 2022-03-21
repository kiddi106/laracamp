<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    

    public function status()
    {
        return $this->hasOne(MstStatus::class, 'id', 'status_id');
    }

    public function purchase_type()
    {
        return $this->hasOne(PurchaseType::class, 'id', 'purchase_type_id');
    }

    public function delivery()
    {
        return $this->hasOne(OrderDelivery::class, 'id', 'delivery_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function receiver()
    {
        return $this->hasOne(OrderReceiver::class, 'id', 'receiver_id');
    }

    public function pick_up()
    {
        return $this->hasOne(OrderPickUp::class, 'id', 'pick_up_id');
    }

    public function received_user()
    {
        return $this->hasOne(User::class, 'id', 'received_by');
    }
}
