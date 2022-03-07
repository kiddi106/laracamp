<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class ProjectAttendance extends Model
{
    use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'Attendances';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'uuid', 'employee_uuid');
    }
}
