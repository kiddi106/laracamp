<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class BrandAsset extends Model
{
    protected $table = 'asset_brands';
    protected $primaryKey = 'brand_id';
    public function modelTypeAsset()
    {
        return $this->belongsTo(ModelTypeAsset::class, 'brand_id');
    }
}
