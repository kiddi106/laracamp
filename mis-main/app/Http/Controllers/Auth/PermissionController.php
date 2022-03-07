<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\Laratrust;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        return view('auth.permissions.list');
    }

    public function list()
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-permissions');
        $canDelete = $laratrust->can('delete-permissions');
        

        $laratrust->user()->roles;
        return Datatables::of(Permission::get())
        ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

            $string = '<div class="btn-group">';
            if ($canUpdate) {
                $string .= '<a href="'.route('config.permission.edit',['permission_id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
            }
            if ($canDelete) {
                $string .= '&nbsp;&nbsp;<a href="'.route('config.permission.remove',['permission_id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
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
        $this->middleware('permission:create-permissions');

        return view('auth.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['name']           = $request->name;
        $data['display_name']   = $request->display_name;
        $data['description']    = $request->description;
        $data['created_at']      = date('Y-m-d H:i:s');

        $permision = new \App\Models\Auth\Permission();
        $permision->name = $request->name;;
        $permision->display_name = $request->display_name;
        $permision->description = $request->description;
        $permision->created_at = date('Y-m-d H:i:s');
        $permision->created_by = Auth::user()->uuid;
        $permision->save();

        dd($permision->save());
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $id = \base64_decode($id);
        $data['permission'] = Permission::find($id);
        
        return view('auth.permissions.edit',$data);

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
        $data['name']           = $request->name;
        $data['display_name']   = $request->display_name;
        $data['description']    = $request->description;
        $data['updated_at']      = date('Y-m-d H:i:s');
        $data['updated_by']      = Auth::user()->id;

        $permision = Permission::findOrFail($id);
        $permision->name = $request->name;;
        $permision->display_name = $request->display_name;
        $permision->description = $request->description;
        $permision->updated_at = date('Y-m-d H:i:s');
        $permision->updated_by = Auth::user()->uuid;
        $permision->save();


        dd($permision->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        $id = \base64_decode($id);
        $permission       = Permission::find($id);
        $permission->delete();

    }
}
