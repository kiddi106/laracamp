<?php

namespace App\Models\Permission;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use RecordSignature;

    protected $table = 'req_permission';
    protected $primaryKey = 'permission_id';
    protected $keyType = 'string';

}
