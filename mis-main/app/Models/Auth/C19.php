<?php

namespace App\Models\Auth;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class C19 extends Model
{
    use RecordSignature;

    protected $table = 'c19';
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_uuid');
    }
}
