<?php

namespace App\Models\Mst;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use RecordSignature;
    protected $table = 'employee_shift';
}
