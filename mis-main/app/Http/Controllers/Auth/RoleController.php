<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\Laratrust;
use App\Models\Auth\Permission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        return view('auth.roles.list');
    }

    public function list()
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-roles');
        $canDelete = $laratrust->can('delete-roles');
        

        $roles = Role::select('roles.*','departments.name as departments')->join('departments','departments.code', '=','roles.department_code');
        
        return Datatables::of($roles)
        ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

            $string = '<div class="btn-group">';
            if ($canUpdate) {
                $string .= '<a href="'.route('config.role.edit',['role_id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
            }
            if ($canDelete) {
                $string .= '&nbsp;&nbsp;<a href="'.route('config.role.remove',['role_id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
            }
            $string .= '</div>';
            return
                $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['departement'] = \App\Models\Auth\Department::all();

        $data['permissions'] = Permission::get();
        $data['roles']       = Role::get();

        return view('auth.roles.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['permissions']      = $request->permission;

        $menu = new \App\Models\Auth\Role();
        $menu->name = $request->name;;
        $menu->display_name = $request->display_name;
        $menu->description = $request->description;
        $menu->parent_id = $request->parent;
        $menu->department_code = $request->departement;
        $menu->created_at = date('Y-m-d H:i:s');
        $menu->created_by = Auth::user()->uuid;
        $menu->save();

        $role       = Role::find($menu->id);
        if($data['permissions'])
        {
            $role->attachPermissions($data['permissions']);
        }
        dd($menu->save());
    }

    public function edit($id)
    {
        $id = \base64_decode($id);
        // $this->middleware('permission:create-role');

        $data['role'] = Role::find($id);

        $data['departement'] = \App\Models\Auth\Department::all();
        $data['roles'] = Role::get();
        
        $data['permissions'] = Permission::get();

        return view('auth.roles.edit',$data);
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


        $oldRole    = $role->permissions;
        $role->detachPermissions($oldRole);

        if($data['permissions'])
        {
            $role->attachPermissions($data['permissions']);
        }

        // $res = Roles::update($data);

        dd($role->save());
    }

    public function remove($id)
    {
        $id = \base64_decode($id);
        $role       = Role::find($id);
        $role->delete();
    }

    public function getAll(Request $request)
    {
        $department_code = $request->department_code;
        $roles = Role::where('department_code', '=', $department_code)->where('name','!=','SUPERADMIN')->get();
        return response()->json($roles);
    }
}
