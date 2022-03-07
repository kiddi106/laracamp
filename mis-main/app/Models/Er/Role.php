<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'roles';

    public function project(){
        return $this->hasOne(Project::class, 'code', 'department_code');
    }
}
