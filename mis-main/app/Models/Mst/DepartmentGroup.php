<?php

namespace App\Models\Mst;

use App\Models\Er\Company;
use Illuminate\Database\Eloquent\Model;

class DepartmentGroup extends Model
{
    protected $connection = 'MESDB';

    public function detail()
    {
        return $this->hasMany(DepartmentGroupDetail::class, 'group_id', 'id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

}
