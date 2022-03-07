<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class StatusAsset extends Model
{
    protected $table = 'asset_status';
    protected $primaryKey = 'status_id';
    public function asset()
    {
        return $this->hasOne(MstAsset::class, 'asset_id');
    }
    public function transaksi()
    {
        return $this->hasMany(TransaksiAsset::class);
    }
}
