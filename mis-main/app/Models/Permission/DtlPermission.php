<?php

namespace App\Models\Permission;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class DtlPermission extends Model
{
    use RecordSignature;

    protected $table = 'req_permission_dtl';

}
