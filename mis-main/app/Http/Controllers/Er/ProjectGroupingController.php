<?php

namespace App\Http\Controllers\Er;

use App\Http\Controllers\Controller;
use App\Models\Mst\DepartmentGroup;
use App\Models\Mst\DepartmentGroupDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectGroupingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('er.projectGroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('er.projectGroup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'company' => ['required'],
            'project' => ['required'],
            'name' => ['required'],
        ]);

        $model = new DepartmentGroup();
        $model->company_id = $request->company;
        $model->name = $request->name;
        
        if ($model->save()) {
            
            foreach ($request->project as $key => $value) {
                $detail = new DepartmentGroupDetail();
                $detail->group_id = $model->id;
                $detail->department_code = $value;
                $detail->save();
            }
            return redirect()->route('er.project.group.index')->with('alert.success', 'Success');

        } else {
            return redirect()->route('er.project.group.index')->with('alert.failed', 'Something Wrong');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $model = DepartmentGroup::find($id);
        foreach ($model->detail as $key => $value) {
            $data[] = $value->department_code; 
        }
        return view('er.projectGroup.edit',['model'=>$model,'data'=>$data]);
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
        $id = base64_decode($id);
        $request->validate([
            'project' => ['required'],
            'name' => ['required'],
        ]);
        $model = DepartmentGroup::find($id);
        $model->name = $request->name;
        if ($model->save()) {
            $del = DepartmentGroupDetail::where('group_id',$model->id)->delete();
            if ($del) {
                foreach ($request->project as $key => $value) {
                    $detail = new DepartmentGroupDetail();
                    $detail->group_id = $model->id;
                    $detail->department_code = $value;
                    $detail->save();
                }
                return redirect()->route('er.project.group.index')->with('alert.success', 'Update Success');
            }
        } else {
            return redirect()->route('er.project.group.index')->with('alert.failed', 'Something Wrong');
        }

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

    public function datatables(Request $request)
    {
        $query = DepartmentGroup::all();
        
        return Datatables::of($query)
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            $string .= '<a href="'.route('er.project.group.edit',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary" title="Edit"><i class="fa fa-edit"></i></a>';
            $string .= '</div>';
            return
                $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getData(Request $request)
    {
        $company_id = $request->company_id;
        $project = DepartmentGroup::where('company_id',$company_id)->get();

        return response()->json($project);
    }
}
