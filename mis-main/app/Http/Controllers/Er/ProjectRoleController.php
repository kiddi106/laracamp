<?php

namespace App\Http\Controllers\Er;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Er\Role;
use App\Models\Er\Permission;
use App\Models\Er\PermissionRole;
use App\Models\Er\Project;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;


class ProjectRoleController extends Controller
{
    public function list()
    {
        return view('er.projectRole.index');
    }

    public function dataTables()
    {

        // $roles = Role::join('MESDB.dbo.departments as d','d.code', '=','roles.department_code')->select('roles.*','d.name as department');
        $roles = Role::with('project')->get();
        
        return Datatables::of($roles)
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            $string .= '<a href="'.route('er.project.roles.edit',['role_id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
            $string .= '</div>';
            return
                $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \base64_decode($id);
        // $this->middleware('permission:create-role');
        $data['permissionRole'] = [];
        $data['role'] = Role::find($id);

        $data['departement'] = Project::all();
        $data['roles'] = Role::get();
        
        $data['permissions'] = Permission::get();
        $permissionRole = PermissionRole::where('role_id',$id)->get();


        foreach ($permissionRole as $key => $value) {
            $data['permissionRole'][] = $value->permission_id;
        }


        // dd($data);
        return view('er.projectRole.edit',$data);
    }

    public function update(Request $request)
    {

        $id                     = \base64_decode($request->id);

        $data['permissions']    = $request->permission;

        $role = Role::findOrFail($id);
        $role->name = $request->name;;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->parent_id = $request->parent;
        $role->department_code = $request->departement;
        $role->updated_at = date('Y-m-d H:i:s');
        $role->updated_by = Auth::user()->uuid;
        $role->save();

        $permissionOld = PermissionRole::where('role_id',$id)->get();

        $deleteOld = PermissionRole::where('role_id', $id)->delete();

        if($data['permissions'])
        {

                foreach ($data['permissions']  as $key => $value) {
                    $newPermission = PermissionRole::create([
                        'role_id' => $id,
                        'permission_id' => $value
                    ]);
                    $newPermission->save();
                }

        }
        dd($role->save());
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
