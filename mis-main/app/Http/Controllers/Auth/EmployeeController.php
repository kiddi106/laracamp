<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Employee;
use App\Models\Auth\Role;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    public function getParent(Request $request)
    {
        $role_id = $request->role_id;
        if ($role_id && $role_id !== '') {
            $role = Role::where('id', '=', $role_id)->first();
            $parentRole = Role::where('id', '=', $role->parent_id)->first();
    
            // $parentEmployee = Employee::whereIn('uuid', function ($query) use ($parentRole) {
            //     $query->select('employee_uuid')
            //         ->from('role_employee')
            //         ->where('role_id', '=', $parentRole->id);
            // })->get();
            $parentEmployee = Employee::whereRoleIs($parentRole->name)->get();
    
            return response()->json($parentEmployee);
        }
        return response()->json();
    }

    public function getEmployee(Request $request)
    {
        $role_id = $request->role_id;
        if ($role_id && $role_id !== '') {
            $role = Role::where('id', '=', $role_id)->first();
            $parentEmployee = Employee::whereRoleIs($role->name)->get();

            return response()->json($parentEmployee);
        }
        return response()->json();
    }
}
