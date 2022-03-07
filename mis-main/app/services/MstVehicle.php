<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MstVehicle
{
    public static function getVehicles()
    {
        return DB::table('mst_vehicle')
        ->get();
    }

    public static function getDrivers()
    {
        return DB::table('mst_driver')
        ->get();
    }

    public static function getVehiclesDrivers()
    {
        return DB::table('mst_vehicle as a')
        ->leftJoin('mst_vehicle_driver as b', 'a.vehicle_id', '=', 'b.vehicle_id')
        ->leftJoin('employees as c', 'b.driver_id', '=', 'c.uuid')
        ->select('a.vehicle_id', 'a.vehicle_license', 'a.vehicle_type', 'a.vehicle_color', 'a.max_passenger', 'c.uuid', 'c.name', 'c.mobile_no')
        ->get();
    }

    public static function getVehicle($id)
    {
        return DB::table('mst_vehicle as a')
            ->leftJoin('mst_vehicle_driver as b', 'a.vehicle_id', '=', 'b.vehicle_id')
            ->leftJoin('employees as c', 'b.driver_id', '=', 'c.uuid')
            ->where('a.vehicle_id', '=', $id)
            ->select('a.*', 'c.uuid', 'c.name', 'c.mobile_no')
            ->first();
    }

    public static function getDriver($id)
    {
        return DB::table('mst_driver as a')
            ->where('a.driver_id', '=', $id)
            ->select('a.*')
            ->first();
    }

    public static function insert($data)
    {
        $db = DB::connection();
        $vehicle = $data['insert']['vehicle'];
        $driver = $data['insert']['driver'];
        $created_by = Auth::user()->uuid;
        $created_at = date('Y-m-d H:i:s');
        try {
            $db->beginTransaction();

            if ($vehicle == 'N') {
                $vehicle_id = $db->table('mst_vehicle')
                ->insertGetId($data['vehicle']);
            }elseif ($vehicle == 'null') {
                $res = false;
                return $res;
            }else {
                $vehicle_id = $vehicle;
            }

            if ($driver !== "") {
                $db->table('mst_vehicle_driver')
                ->insert(['vehicle_id' => $vehicle_id, 'driver_id' => $driver, 'created_at' => $created_at, 'created_by' => $created_by ]);
            }

            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            $res = false;
        }
        return $res;
    }   
    
    public static function update($data)
    {
        $db = DB::connection();
        $driver = $data['update_driver']['driver'];
        try {
            $db->beginTransaction();

            $db->table('mst_vehicle')
            ->where($data['where'])
            ->update($data['update']);

            if ($driver == "") {
                $db->table('mst_vehicle_driver')
                ->where($data['where'])
                ->delete();
            }else {

                $db->table('mst_vehicle_driver')
                ->where($data['where'])
                ->delete();
                
                $db->table('mst_vehicle_driver')
                ->insert(['vehicle_id' => $data['vehicle']['vehicle_id'], 'driver_id' => $driver, 'created_at' => $data['update']['updated_at'], 'created_by' => $data['update']['updated_by'] ]);
            }
            
            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            $res = false;
        }

        return $res;
    }   
    
    public static function updateDriver($data)
    {
        $db = DB::connection();
        try {
            $db->beginTransaction();

            $db->table('mst_driver')
            ->where($data['where'])
            ->update($data['update']);
            
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
        $db = DB::connection();
        try {
            $db->beginTransaction();
            
            $db->table('mst_vehicle_driver')
            ->where('vehicle_id', '=', $id)
            ->delete();
            
            $db->table('mst_vehicle')
            ->where('vehicle_id', '=', $id)
            ->delete();

            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            dd($e);
            $res = false;
        }
        return $res;
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