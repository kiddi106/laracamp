<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $connection = 'PAYROLLDB';
    protected $table = 'status';
    public $timestamps = false;

}
