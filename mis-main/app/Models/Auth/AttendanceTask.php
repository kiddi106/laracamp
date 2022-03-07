<?php

namespace App\Models\Auth;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class AttendanceTask extends Model
{
    use RecordSignature;

    protected $table = 'attendance_tasks';
}
