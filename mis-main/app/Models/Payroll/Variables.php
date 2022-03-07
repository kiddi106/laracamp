<?php

namespace App\Models\Payroll;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Variables extends Model
{
    use RecordSignature;
    
    protected $connection = 'PAYROLLDB';
    protected $table = 'variables';

    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }
}
