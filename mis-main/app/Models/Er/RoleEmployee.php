<?php

namespace App\Models\Er;

// use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class RoleEmployee extends Model
{
    // use RecordSignature;
    protected $connection = 'MESDB';
    protected $table = 'role_employee';
    protected $fillable = ['role_id', 'employee_uuid', 'user_type'];
    public $incrementing = false;
    public $timestamps = false;

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
