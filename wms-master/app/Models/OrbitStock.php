<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrbitStock extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['router_id', 'simcard_id', 'status_id', 'purchase_type_id', 'location', 'order_id', 'order_item_id', 'created_by'];

    public function status()
    {
        return $this->hasOne(MstStatus::class, 'id', 'status_id');
    }

    public function router()
    {
        return $this->hasOne(Router::class, 'id', 'router_id');
    }

    public function simcard()
    {
        return $this->hasOne(Simcard::class, 'id', 'simcard_id');
    }

    public function purchase_type()
    {
        return $this->hasOne(PurchaseType::class, 'id', 'purchase_type_id');
    }

    public function created_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
