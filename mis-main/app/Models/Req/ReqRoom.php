<?php

namespace App\Models\Req;

use App\Helpers\Traits\RecordSignature;
use App\Models\Auth\Department;
use App\Models\Auth\Employee;
use App\Models\Mst\MstRoom;
use Illuminate\Database\Eloquent\Model;

class ReqRoom extends Model
{
    use RecordSignature;

    protected $table = 'req_room';

    public function room()
    {
        return $this->hasOne(MstRoom::class, 'code', 'room_code');
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'code', 'dept_code');
    }

    public function requester()
    {
        return $this->hasOne(Employee::class, 'uuid', 'request_for');
    }
}
