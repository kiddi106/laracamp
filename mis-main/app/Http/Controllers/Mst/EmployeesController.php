<?php

namespace App\Http\Controllers\Mst;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\Employee;
use App\Models\Auth\Department;
use App\Models\Auth\Role;
use App\Services\MstVehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\Laratrust;
use Illuminate\Support\Facades\Validator;


class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mst.employees.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mst.employees.changePassword');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \base64_decode($id);
        $data['employee'] = Employee::find($id);
        $data['department'] = Department::find($data['employee']->roles[0]->department_code);
        $parentRole = Role::where('id', '=', $data['employee']->roles[0]->parent_id)->first();
        $data['directLeader'] = Employee::whereRoleIs($parentRole->name)->first();
        return view('mst.employees.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $user = auth()->user()->password;
        $this->validate($request, [
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user)) {
                    return $fail(__('The :attribute is incorrect.'));
                }
            }]
        ]);
        $password = Hash::make($request->password);
        Employee::find(auth()->user()->uuid)
            ->update([
                'password' => $password, 
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->uuid
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dataTables(Request $request)
    {
        $query = Employee::query();
        $query->join('role_employee as re','re.employee_uuid','=','employees.uuid')
            ->join('roles as r','r.id','=','re.role_id')
            ->join('departments as d','r.department_code','=','d.code')
            ->select('employees.*','r.name as role','d.name as department');

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('action', function ($model) {
            return '<a href="'.route('mst.employee.show',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-secondary btn-show" title="Show"><i class="fa fa-eye"></i> show</a>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
