<?php

namespace App\Models\Er;

// use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class EmployeeProject extends Model
{
    // use RecordSignature;

    protected $table = 'employee_project';
    protected $fillable = ['employee_uuid', 'project_code'];
    public $incrementing = false;
    public $timestamps = false;
}
