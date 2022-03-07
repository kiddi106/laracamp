<?php

namespace App\Models\Mst;

use Illuminate\Database\Eloquent\Model;

class EmployeeDepartment extends Model
{
    protected $table = 'asset_emp_department';

    public function employee()
    {
        return $this->belongsTo(EmployeeAsset::class, 'employee_uuid');
    }
    public function department()
    {
        return $this->belongsTo(DepartmentAsset::class, 'department_code');
    }
}
