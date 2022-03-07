<?php

namespace App\Models\Mst;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mst\MstRoom;

class MstArea extends Model
{
    use RecordSignature;

    protected $table = 'mst_area';
    protected $primaryKey = 'area_code';
    protected $keyType = 'string';
    public $incrementing = false;

    public function room_code()
    {
        return $this->hasMany(MstRoom::class, 'area_code', 'area');
    }
}
