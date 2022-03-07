<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class PayrollEmployeeVariables extends Model
{
    protected $connection = 'PAYROLLDB';
    protected $table = 'payroll_employee_variables';
    public $timestamps = false;

    public function variable()
    {
        return $this->hasOne(Variables::class, 'id', 'variable_id');
    }
}
