<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Ptkp extends Model
{
    
    protected $connection = 'PAYROLLDB';
    protected $table = 'ptkp';
    public $timestamps = false;
}
