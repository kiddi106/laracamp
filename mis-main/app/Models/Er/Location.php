<?php

namespace App\Models\Er;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use App\Models\Er\Project;

class Location extends Model
{
    use RecordSignature;

    protected $connection = 'MESDB';
    protected $table = 'mst_location';

    public function department()
    {
        return $this->hasOne(Project::class, 'code', 'department_code');
    }
}
