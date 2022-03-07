<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    // use RecordSignature;

    protected $connection = 'MESDB';
    protected $fillable = ['permission_id', 'role_id'];
    protected $table = 'permission_role';
    public $timestamps = false;

}
