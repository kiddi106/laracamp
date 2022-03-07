<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class ProjectPayrollVariables extends Model
{
    protected $connection = 'PAYROLLDB';
    protected $table = 'project_payroll_variables';
    public $timestamps = false;

    public function variable()
    {
        return $this->hasOne(Variables::class, 'id', 'variable_id');
    }

}
