<?php

namespace App\Models\Auth;

use App\Helpers\Traits\BaseModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laratrust\Traits\LaratrustUserTrait;

class Employee extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable, BaseModel;

    protected $guard = 'employee';
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

    public function roleEmployees(){
        return $this->hasMany(RoleEmployee::class, 'employee_uuid', 'uuid');
    }

    private static function allChildRole($parent_id)
    {
        $retval = [];
        if ($parent_id != null) {
            $retval[] = Role::find($parent_id)->id;
            $roles = Role::where('parent_id', '=', $parent_id)->get();
            if ($roles) {
                foreach ($roles as $role) {

                    $childs = Employee::allChildRole($role->id);
                    if (count($childs) > 0) {
                        array_push($retval, ...$childs);
                    }
                }
            }
        }
        return $retval;
    }

    public static function roleChilds(){
        $retval = [];
        $roleEmployees = Auth::user()->roleEmployees;
        foreach ($roleEmployees as $re) {
            $roles = $re->role;
            $childs = Employee::allChildRole($roles->id);
            array_push($retval, ...$childs);
        }
        return $retval;
    }
}
