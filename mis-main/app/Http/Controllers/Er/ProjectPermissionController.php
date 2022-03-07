<?php

namespace App\Http\Controllers\Er;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Er\Project;
use App\Models\Er\Role;
use App\Models\Er\Permission;
use App\Models\Er\Employee;
use App\Models\Er\PermissionEmployee;
use Illuminate\Support\Facades\Auth;

class ProjectPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['departments'] = Project::all();

        $data['roles'] = Role::all();


        return view('er.projectPermission.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function empPermission(Request $request)
    {
        $data['PermissionEmployee'] = [];

        $uuid = $request->emp;

        $data['emp'] = Employee::where('uuid',$uuid)->get();

        $data['permissions'] = Permission::all();

        $PermissionEmployee = PermissionEmployee::where('employee_uuid',$uuid)->get();


        foreach ($PermissionEmployee as $key => $value) {
            $data['PermissionEmployee'][] = $value->permission_id;
        }

        return view('er.projectPermission.empPermission',$data)->render();

    }

    public function empUpdate(Request $request)
    {
        $data['permissions']    = $request->permission;
        $uuid    = $request->uuid;

        $deleteOld = PermissionEmployee::where('employee_uuid', $uuid)->delete();

        if($data['permissions'])
        {
                foreach ($data['permissions']  as $key => $value) {
                    $newPermission = PermissionEmployee::create([
                        'employee_uuid' => $uuid,
                        'permission_id' => $value,
                        'user_type'=>'App\Models\Auth\Employee'
                    ]);
                    $newPermission->save();
                }
        }

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
        //
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
    public function update(Request $request, $id)
    {
        //
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
}
