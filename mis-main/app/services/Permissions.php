<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Auth\User;

class Permissions
{
    public static function insert($data)
    {
        $db = DB::connection();
        $id = $db->table('permissions')
            ->insert($data);            
    }
    public static function remove($id)
    {
        
        $permission       = Permission::find($id);
        $permission->delete();
    }      

}