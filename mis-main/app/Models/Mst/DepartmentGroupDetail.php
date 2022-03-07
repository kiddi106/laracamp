<?php

namespace App\Models\Mst;

use App\Models\Auth\Department;
use Illuminate\Database\Eloquent\Model;

class DepartmentGroupDetail extends Model
{
    protected $connection = 'MESDB';
    public $timestamps = false;

    public function project()
    {
        return $this->hasOne(Department::class, 'code', 'department_code');
    }

}
