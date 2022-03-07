<?php

namespace App\Models\Mst;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mst\MstArea;
use App\Models\Mst\ReqRoom;

class MstRoom extends Model
{
    use RecordSignature;

    protected $table = 'mst_room';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    public function area()
    {
        return $this->belongsTo(MstArea::class, 'area');
    }

    public function reqRoom()
    {
        return $this->hasMany(ReqRoom::class, 'code', 'room_code');
    }
}
