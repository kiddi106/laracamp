<?php

namespace App\Models\Mst;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use RecordSignature;

    protected $table = 'mst_shift';
    protected $primaryKey = 'shift_cd';
    protected $keyType = 'string';
    public $incrementing = false;
}
