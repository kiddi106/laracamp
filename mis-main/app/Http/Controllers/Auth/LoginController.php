<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Employee;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust\Laratrust;
use App\Models\Auth\Menu;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers{
        redirectPath as laravelRedirectPath;
    }
    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectPath()
    {
        if (Auth::check()) 
        {
            $user = Auth::user();
            
            $employee = Employee::find($user->uuid);
            $employee->last_login = date('Y-m-d H:i:s');
            $employee->save();
            // $user = Employee::getEmployee($user->id);
            $laratrust = new Laratrust(app());
            $role = $laratrust->user()->roles;
            $navbar = Menu::join('menu_role','menu_role.menu_id','=','menus.id')
                        ->where(array('role_id'=>$role[0]->id,'level'=>1))
                        ->orderBy('order_no', 'asc')
                        ->select('menus.*',DB::raw('CASE
                            WHEN id in (select menu_id from menus) THEN 1
                            END as count'))
                        ->get();
            $navbar2 = Menu::join('menu_role','menu_role.menu_id','=','menus.id')
                        ->where(array('role_id'=>$role[0]->id,'level'=>2))
                        ->orderBy('order_no', 'asc')
                        ->select('menus.*',DB::raw('CASE
                            WHEN id in (select menu_id from menus) THEN 1
                            END as count'))
                        ->get();
            $navbar3 = Menu::join('menu_role','menu_role.menu_id','=','menus.id')
                        ->where(array('role_id'=>$role[0]->id,'level'=>3))
                        ->orderBy('order_no', 'asc')
                        ->select('menus.*',DB::raw('CASE
                            WHEN id in (select menu_id from menus) THEN 1
                            END as count'))
                        ->get();

            session(
                [
                    "navbar"=>$navbar,
                    "navbar2"=>$navbar2,
                    "navbar3"=>$navbar3,
                    'user'=>$user]);
        }
        
        return $this->laravelRedirectPath();
    }

}
