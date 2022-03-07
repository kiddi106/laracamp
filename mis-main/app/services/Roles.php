<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Auth\User;

class Roles
{
    public static function get()
    {
        return DB::table('roles as a')
            ->join('departement_role as b','a.id','b.role_id')
            ->join('mst_departement as c','c.departement_cd','b.departement_cd')
            ->select('*');
    }

    public static function getRole($id)
    {
        return DB::table('roles as a')
            ->join('departement_role as b','a.id','b.role_id')
            ->join('mst_departement as c','c.departement_cd','b.departement_cd')
            ->where('a.id','=',$id)
            ->select('*')
            ->get();
    }

    public static function getDepartementRole($id)
    {
        return DB::table('roles as a')
            ->join('departement_role as b','a.id','b.role_id')
            ->where('b.departement_cd','=',$id)
            ->select('*')
            ->get();
    }
    public static function getDepartements()
    {
        return DB::table('mst_departement')
            ->select('*')
            ->get();
    }

    public static function insert($data)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();
            $id = DB::table('roles')
            ->insertGetId($data['insert']);

            DB::table('departement_role')
            ->insert(
                array('role_id' => $id, 'departement_cd' => $data['departement'])
            );

            $role       = Role::find($id);
            if($data['permissions'])
            {
                $role->attachPermissions($data['permissions']);
            }
            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            dd($e);
            $res = false;
        }

        return $res;
    }    

    public static function update($data)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();

            $id = $db->table('roles')
            ->where('id',$data['where']['id'])
            ->update($data['insert']);

            $role       = Role::find($data['where']['id']);
            $oldRole    = $role->permissions;

            DB::table('departement_role')
            ->where('role_id' ,'=', $data['where']['id'])
            ->delete();

            DB::table('departement_role')
            ->insert(
                array('role_id' => $data['where']['id'], 'departement_cd' => $data['departement'])
            );

            $role->detachPermissions($oldRole);
            if($data['permissions'])
            {
                $role->attachPermissions($data['permissions']);
            }

            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            dd($e);
            $res = false;
        }

        return $res;
    } 

    public static function remove($id)
    {
        
        $role       = Role::find($id);
        $role->delete();
        DB::table('departement_role')
        ->where('role_id' ,'=', $id)
        ->delete();
    }          

}