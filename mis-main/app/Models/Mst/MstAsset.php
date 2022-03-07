<?php

namespace App\Models\Mst;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\Employee;

class MstAsset extends Model
{
    use RecordSignature;
    protected $table = 'asset';

    public function modelTransaksi()
    {
        return $this->hasOne(TransaksiAsset::class, 'asset_id');
    }
    public function brand()
    {
        return $this->hasOne(BrandAsset::class, 'brand_id', 'brand_id');
    }
    public function modelType()
    {
        return $this->hasOne(ModelTypeAsset::class, 'model_type_id', 'model_type_id');
    }
    public function category()
    {
        return $this->hasOne(CategoryAsset::class, 'category_id', 'category_id');
    }
    public function create_by()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
    public function location()
    {
        return $this->hasOne(LocationAsset::class, 'city_id', 'city_id');
    }
}

