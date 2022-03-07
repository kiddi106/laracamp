<?php

namespace App\Models\Mst;

use App\Helpers\Traits\BaseModel;
use Illuminate\Database\Eloquent\Model;

class EmployeeAsset extends Model
{
    use BaseModel;
    protected $table = 'asset_employee';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    public function department()
    {
        return $this->hasMany(EmployeeDepartment::class, 'employee_uuid');
    }

    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
}
