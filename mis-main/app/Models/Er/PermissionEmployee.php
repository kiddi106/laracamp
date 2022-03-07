<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class PermissionEmployee extends Model
{
    // use RecordSignature;

    protected $connection = 'MESDB';
    protected $fillable = ['permission_id', 'employee_uuid','user_type'];
    protected $table = 'permission_employee';
    public $timestamps = false;

}
