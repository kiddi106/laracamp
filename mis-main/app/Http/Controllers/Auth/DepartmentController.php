<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\Department;
use Illuminate\Support\Facades\Auth;
use Laratrust\Laratrust;
use Yajra\DataTables\DataTables;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        return view('auth.department.create',compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dprtmnt = new \App\Models\Auth\Department();
        $dprtmnt->name = $request->name;
        $dprtmnt->code = $request->code;
        $dprtmnt->parent_code = $request->parent_code;
        $dprtmnt->created_at = date('Y-m-d H:i:s');
        $dprtmnt->created_by = Auth::user()->uuid;
        $dprtmnt->save();
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
        $id = \base64_decode($id);
        $department = Department::find($id);
        $departments = Department::all();
        return view('auth.department.edit',compact('department','departments'));
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
        $dprtmnt       = Department::find($id);
        $dprtmnt->name = $request->name;
        $dprtmnt->code = $request->code;
        $dprtmnt->parent_code = $request->parent_code;
        $dprtmnt->updated_at = date('Y-m-d H:i:s');
        $dprtmnt->updated_by = Auth::user()->uuid;
        $dprtmnt->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = \base64_decode($id);
        $dprtmnt       = Department::where('code','=',$id);
        $dprtmnt->delete();
    }

    public function dataTables()
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-departement');
        $canDelete = $laratrust->can('delete-departement');

        return DataTables::of(Department::all())
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

                $string = '<div class="btn-group">';
                if ($canUpdate) {
                    $string .= '<a href="'.route('config.department.edit',['code' => base64_encode($model->code)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if ($canDelete) {
                    $string .= '&nbsp;&nbsp;<a href="'.route('config.department.destroy',['code' => base64_encode($model->code)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                }
                $string .= '</div>';
                return
                    $string;
            })
            ->make(true);
    }
}
