<?php

namespace App\Models\Auth;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use RecordSignature;

    protected $table = 'attendances';

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_uuid');
    }
}
