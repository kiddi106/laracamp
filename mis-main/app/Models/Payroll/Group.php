<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use RecordSignature;
    protected $connection = 'PAYROLLDB';
}
