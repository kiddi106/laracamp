<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $role_ids = [];
        foreach ($user->roles as $role) {
            $role_ids[] = $role->id;
        };

        $menus = $this->getMenus($role_ids, null);
        $request->session()->put('menus', $menus);
    }

    /**
     * The user has been authenticated.
     *
     * @param  array  $role_ids
     * @param  int    $menu_id
     * @return array
     */
    private function getMenus($role_ids, $menu_id)
    {
        $query = DB::table('menus')
            ->whereIn('id', function ($query) use ($role_ids) {
                    $query->select('menu_id')->from('menu_role')
                ->whereIn('role_id', $role_ids);
            });
        if ($menu_id === null) {
            $query->whereNull('menu_id');
        } else {
            $query->where('menu_id', '=', $menu_id);
        }
        $menus = $query->get();

        foreach ($menus as $key => $menu) {
            $childs = $this->getMenus($role_ids, $menu->id);
            $menus[$key]->menus = $childs;
        }

        return $menus;
    }
}
