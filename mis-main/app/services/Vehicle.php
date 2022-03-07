<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Vehicle
{

    public static function getReqVehicles()
    {
        return DB::table('req_vehicle as a')
            ->leftJoin('mst_vehicle as b', 'a.vehicle_id', '=', 'b.vehicle_id')
            ->leftJoin('employees as c', 'a.driver_id', '=', 'c.uuid')
            ->select('a.*', 'b.vehicle_id', 'b.vehicle_license', 'b.vehicle_type', 'c.uuid', 'c.name', 'c.mobile_no');
    }

    public static function getReqVehiclesDriver()
    {
        return DB::table('req_vehicle as a')
            ->leftJoin('mst_vehicle as b', 'a.vehicle_id', '=', 'b.vehicle_id')
            ->leftJoin('employees as c', 'a.driver_id', '=', 'c.uuid')
            ->select('a.*', 'b.vehicle_id', 'b.vehicle_license', 'b.vehicle_type', 'c.uuid', 'c.name', 'c.mobile_no')
            ->where('a.driver_id','=', Auth::user()->uuid);
    }

    public static function getReqVehiclesTes()
    {
        return DB::table('req_vehicle as a')
            ->select('a.*');
    }

    public static function getReqVehicle($id)
    {
        return DB::table('req_vehicle as a')
            ->where('a.req_vehicle_id', '=', $id)
            ->select('a.*')
            ->first();
    }

    public static function getDriver($id)
    {
        return DB::table('mst_vehicle_driver as a')
            ->join('mst_vehicle as b', 'a.vehicle_id', '=', 'b.vehicle_id')
            ->join('employees as c', 'a.driver_id', '=', 'c.uuid')
            ->where('b.vehicle_id', '=', $id)
            ->select('b.vehicle_id', 'c.uuid', 'c.name', 'c.mobile_no')
            ->first();
    }

    public static function insert($data)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();

            $db->table('req_vehicle')
                ->insertGetId($data['insert']);

            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            dd($e);
            $db->rollBack();
            $res = false;
        }
        return $res;
    }   
    
    public static function update($data)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();

            $db->table('req_vehicle')
            ->where($data['where'])
            ->update($data['update']);
            
            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            $res = false;
        }

        return $res;
    }   
    
    public static function remove($id)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();
            
            $db->table('req_vehicle')
            ->where('req_vehicle_id', '=', $id)
            ->update(['req_vehicle_cd' => 'C']);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            dd($e);
        }
    }   
    
    public static function removeDriver($id)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();
            
            $db->table('mst_vehicle_driver')
            ->where('driver_id', '=', $id)
            ->delete();
            
            $db->table('mst_driver')
            ->where('driver_id', '=', $id)
            ->delete();

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            dd($e);
        }
    }   

}