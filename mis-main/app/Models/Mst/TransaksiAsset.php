<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class TransaksiAsset extends Model
{
    protected $table = 'asset_transaksi';
    protected $primaryKey = 'transaksi_id';
    public function asset()
    {
        return $this->belongsTo(MstAsset::class, 'asset_id');
    }
    public function status()
    {
        return $this->hasOne(StatusAsset::class, 'status_id', 'status_id');
    }
    public function user()
    {
        return $this->hasOne(EmployeeAsset::class, 'checkout_to');
    }
    public function checkout()
    {
        return $this->hasOne(CheckoutAsset::class, 'checkout_id', 'checkout_id');
    }
    public function checkin()
    {
        return $this->hasOne(CheckInAsset::class, 'checkin_id', 'checkin_id');
    }
}
