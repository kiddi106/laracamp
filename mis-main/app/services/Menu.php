<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Auth\User;

class Menu
{
    public static function getMenus()
    {
        // return DB::table('menu_role as a')
        //     ->join('menus as b', 'a.menu_id', '=', 'b.id')
        //     ->join('roles as c', 'a.role_id', '=', 'c.id')
        //     ->select('c.name as nama_role', 'b.name as menu','b.url','b.icon', 'b.id as id','c.id as id_role');

        return DB::table('menus as a')
        ->select('a.*')
        ->get();
    }

    public static function getMenu($id)
    {
        return DB::table('menus as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->get();
    }

    public static function getMenuRole($id)
    {

        return DB::table('menu_role as a')
            ->join('roles as c', 'a.role_id', '=', 'c.id')
            ->where('a.menu_id', '=', $id)
            ->select('c.*')
            ->get();
    }

    public static function insert($data)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();
            $id = $db->table('menus')
            ->insertGetId($data['insert']);


            foreach ($data['role'] as $key => $value) {
                $db->table('menu_role')
                ->insert(
                    ['menu_id' => $id, 'role_id' => $value]
                );
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
            $role = Menu::getMenuRole($data['where']['id']);

            // dd($role);

                Menu::removeMenuRole($data['where']['id']);


            foreach ($data['role'] as $key => $value) {
                $db->table('menu_role')
                ->insert(
                    ['menu_id' => $data['where']['id'], 'role_id' => $value]
                );
            }

            $db->table('menus')
            ->where($data['where'])
            ->update($data['insert']);
            
            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            dd($e);
            $res = false;
        }

        return $res;
    }   
    
    public static function navbar($role_id)
    {
        return DB::table('menu_role as a')
            ->join('menus as b', 'a.menu_id', '=', 'b.id')
            ->where('a.role_id','=',$role_id)
            ->where('b.level','=','1')
            ->select('b.*',DB::raw('(SELECT COUNT(id) FROM menus WHERE menu_id = b.id) as count'))
            ->orderBy('b.order_no', 'asc')
            ->get();
    }   
    
    public static function navbar2($role_id)
    {
        return DB::table('menu_role as a')
            ->join('menus as b', 'a.menu_id', '=', 'b.id')
            ->where('a.role_id','=',$role_id)
            ->where('b.level','=','2')
            ->select('b.*',DB::raw('(SELECT COUNT(id) FROM menus WHERE menu_id = b.id) as count'))
            ->orderBy('b.order_no', 'asc')
            ->get();
    }   

    public static function navbar3($role_id)
    {
        return DB::table('menu_role as a')
            ->join('menus as b', 'a.menu_id', '=', 'b.id')
            ->where('a.role_id','=',$role_id)
            ->where('b.level','=','3')
            ->select('b.*',DB::raw('(SELECT COUNT(id) FROM menus WHERE menu_id = b.id) as count'))
            ->orderBy('b.order_no', 'asc')
            ->get();
    }       

    public static function removeMenuRole($id)
    {
        $db = DB::connection();
        $db->table('menu_role')
        ->where('menu_id', '=', $id)
        ->delete();
    }   

    public static function remove($id)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();
            
            $db->table('menus')
            ->where('id', '=', $id)
            ->delete();

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            dd($e);
        }

    }   
}