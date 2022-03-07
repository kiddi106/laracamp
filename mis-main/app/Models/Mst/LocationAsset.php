<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class LocationAsset extends Model
{
    protected $table = 'asset_province_city';
    protected $primaryKey = 'city_id';
    public $incrementing = false;
}
