<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class DepartmentAsset extends Model
{
    protected $table = 'asset_department';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    public function employee()
    {
        return $this->hasMany(EmployeeDepartment::class, 'department_code');
    }
}
