<?php

namespace App\Models\Er;

use Illuminate\Database\Eloquent\Model;

class EmployeeHist extends Model
{
    protected $connection = 'MESDB';
    protected $table = 'employee_hist';

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_uuid', 'uuid');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'code', 'department_code');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
