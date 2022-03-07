<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use App\Models\Er\Employee;
use Illuminate\Database\Eloquent\Model;

class PayrollEmployee extends Model
{
    use RecordSignature;
    
    protected $connection = 'PAYROLLDB';
    protected $table = 'payroll_employee';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'uuid', 'employee_uuid');
    }

    public function payroll()
    {
        return $this->hasOne(ProjectPayroll::class, 'id', 'project_payroll_id');
    }

    public function variables()
    {
        return $this->hasMany(PayrollEmployeeVariables::class,'payroll_employee_id','id');
    }

    public function ptkp()
    {
        return $this->hasOne(Ptkp::class, 'id', 'ptkp_id');
    }

}
