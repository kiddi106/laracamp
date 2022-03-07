<?php

namespace App\Models\Mst;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class TypePermission extends Model
{
    use RecordSignature;

    protected $table = 'mst_type_permission';
    protected $primaryKey = 'type_permission_cd';
    protected $keyType = 'string';
    public $incrementing = false;
}
