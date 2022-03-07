<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'attendances';

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_uuid');
    }
}
