<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Employee;
use App\Models\Auth\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:employees'],
            'password' => ['required', 'string', 'confirmed'],
            'role_id' => ['required', 'integer'],
            'empl_id' => ['max:20'],
            'ext_no' => ['max:5'],
            'mobile_no' => ['max:20']
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if (!$request->loc) 
        {
            return redirect()->route('register')->with('alert.failed', 'Failed, Please Allow to Access Location');
        }

        $loc = Crypt::decryptString($request->loc);

        // dd($request);
        $this->validator($request->all())->validate();
        $employee = new Employee();
        $employee->name = $request->name;
        $employee->email = $request->email.'@mitracomm.com';
        $employee->password = Hash::make($request->password);
        $employee->parent_uuid = $request->parent_uuid;
        $employee->empl_id = $request->empl_id;
        $employee->join_date = $request->join_date;
        $employee->ext_no = $request->ext_no;
        $employee->mobile_no = $request->mobile_no;
        $employee->dob = $request->dob;
        $employee->pob = $request->pob;
        $employee->location_register = $loc;
        $employee->save();

        if ($employee->uuid) {
            $role = Role::where('id', '=', $request->role_id)->first();
            $employee->attachRole($role);
            // $this->guard()->login($employee);
        }

        // return redirect($this->redirectTo)->with('alert.success', 'Success Registered');
        return redirect()->route('login')->with('alert.success', 'Success Registered');
    }

    public function getLocation(Request $request)
    {

        $loc = Crypt::encryptString($request->loc);

        return $loc;
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('employee');
    }
}
