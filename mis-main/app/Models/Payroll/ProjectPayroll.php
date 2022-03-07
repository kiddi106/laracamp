<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use App\Models\Er\Project;
use App\Models\Mst\DepartmentGroup;
use Illuminate\Database\Eloquent\Model;

class ProjectPayroll extends Model
{
    use RecordSignature;
    
    protected $connection = 'PAYROLLDB';
    protected $table = 'project_payroll';

    public function project()
    {
        return $this->hasOne(Project::class, 'code', 'project_code');
    }

    public function group()
    {
        return $this->hasOne(DepartmentGroup::class, 'id', 'project_code');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function payroll()
    {
        return $this->hasOne(PayrollEmployee::class, 'project_payroll_id', 'id');
    }
}
