<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class ModelTypeAsset extends Model
{
    protected $table = 'asset_model_type';
    protected $primaryKey = 'model_type_id';

    public function brand()
    {
        return $this->hasOne(BrandAsset::class, 'id', 'brand_id');
    }
}
