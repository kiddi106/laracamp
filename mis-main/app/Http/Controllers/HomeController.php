<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auth\Attendance;
use App\Models\Auth\Employee;
use App\Models\Auth\Menu;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laratrust\Laratrust;
use App\Models\Mst\EmployeeShift;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = date('Y-m-d');
        $employee_uuid = Auth::user()->uuid;
        $check_in = false;
        $check_out = false;
        $id_shift = false;
        $attendance_id = false;

        $user = Auth::user();
        $laratrust = new Laratrust(app());
        $role = $laratrust->user()->roles;
        if (!$employee_uuid) {
            return redirect(route('login'));
        }
        
        if (!session('navbar')) {
            $navbar = Menu::join('menu_role','menu_role.menu_id','=','menus.id')
            ->where(array('role_id'=>$role[0]->id,'level'=>1))
            ->orderBy('order_no', 'asc')
            ->select('menus.*',DB::raw('CASE
            WHEN id in (select menu_id from menus) THEN 1
            END as count'))
            ->get();

            session(['navbar' => $navbar]);
        }

        if (!session('navbar2')) {
            $navbar2 = Menu::join('menu_role','menu_role.menu_id','=','menus.id')
                        ->where(array('role_id'=>$role[0]->id,'level'=>2))
                        ->orderBy('order_no', 'asc')
                        ->select('menus.*',DB::raw('CASE
                            WHEN id in (select menu_id from menus) THEN 1
                            END as count'))
                        ->get();

            session(['navbar2' => $navbar2]);
        }

        if (!session('navbar3')) {
            $navbar3 = Menu::join('menu_role','menu_role.menu_id','=','menus.id')
                        ->where(array('role_id'=>$role[0]->id,'level'=>3))
                        ->orderBy('order_no', 'asc')
                        ->select('menus.*',DB::raw('CASE
                            WHEN id in (select menu_id from menus) THEN 1
                            END as count'))
                        ->get();
            
            session(['navbar3' => $navbar3]);
        }

        // $attendance = Attendance::where(array('date'=> $today,'employee_uuid'=>$employee_uuid))->whereNotNull('time_in')->first();

        $besok = date('Y-m-d',(strtotime ( '+1 day' , strtotime ( $today) ) ));
        $kemarin = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $today) ) ));

        // $shift = EmployeeShift::whereBetween('date', [$kemarin, $today])->where('employee_uuid',$employee_uuid)->orderBy('date', 'asc')->first();
        $shift = EmployeeShift::whereBetween('date_in', [$kemarin, $today])->where('employee_uuid',$employee_uuid)->whereNull('attendance_id')->orderBy('date', 'asc')->first();

        // dd($shift);
        if ($shift) 
        {   
            $out = Attendance::where('employee_uuid','=',$employee_uuid)->whereNotNull('time_in')->whereNull('time_out')->whereBetween('date', [$kemarin, $besok])->first();
            if ($out) {
                $check_out = true;
                $attendance_id = $out->id;
            }
            else {
                $check_out = false;
                $check = EmployeeShift::where('employee_uuid','=',$employee_uuid)->whereBetween('date_in', [$kemarin, $today])->whereNull('attendance_id')->orderBy('date','asc')->first();
                if ($check) 
                {
                    $check_in = true;
                    $id_shift = $check->id;
                }
                else
                {
                    $out = Attendance::where('employee_uuid','=',$employee_uuid)->whereNotNull('time_in')->whereNull('time_out')->whereBetween('date', [$today, $besok])->first();
                    $check_in = false;
                    if ($out) {
                        $check_out = true;
                        $attendance_id = $out->id;
                    }
                    else {
                        $check_out = false;
                    }
                }
            }

        }
        else {
            $attendance = Attendance::where(array('date'=> $today,'employee_uuid'=>$employee_uuid))->first();
            if ($attendance) {
                // $check_out = true;
                // $attendance_id = $attendance->id;
                if ($attendance->time_out != null) {
                    $check_out = false;
                    $check_in = false;
                }
                else {
                    $check_out = true;
                    $attendance_id = $attendance->id;
                }
            }
            else
            {
                $check_in = true;
            }
        }

        return view('home', ['check_in' => $check_in,
                            'check_out'=>$check_out,
                            'id_shift'=>$id_shift,
                            'attendance_id'=>$attendance_id]);
    }
}
