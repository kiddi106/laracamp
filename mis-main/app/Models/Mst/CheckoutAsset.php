<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class CheckoutAsset extends Model
{
    protected $table = 'asset_checkout_log';
    protected $primaryKey = 'checkout_id';
    public function checkoutTo()
    {
        return $this->belongsTo(EmployeeAsset::class, 'checkout_to');
    }
}
