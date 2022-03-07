<?php

namespace App\Models\Er;

use App\Helpers\Traits\BaseModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class Employee extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable, BaseModel;

    protected $connection = 'MESDB';
    protected $table = 'employees';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roleEmployee(){
        return $this->hasOne(RoleEmployee::class, 'employee_uuid', 'uuid');
    }
    public function project(){
        return $this->hasOne(Project::class, 'code', 'department_code');
    }
}
