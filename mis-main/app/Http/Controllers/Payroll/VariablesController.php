<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Group;
use App\Models\Payroll\Variables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust\Laratrust;
use Yajra\DataTables\Facades\DataTables;

class VariablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $t = Variables::all();

        // dd($t);
        return view('payroll.variable.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['model'] = new Variables();
        return view('payroll.variable.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Variables();
        $model->name = $request->name;
        $model->counter = $request->counter;
        $model->type = $request->type;
        $model->model = $request->model;
        $model->percentage = $request->percentage;
        $model->tax_counter = $request->tax_counter;
        $model->created_at = now();
        $model->created_by = Auth::user()->uuid;
        $model->save();

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
        $id = base64_decode($id);
        $data['model'] = Variables::find($id);

        return view('payroll.variable.form',$data);
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

        $v = Variables::find($id);
        $v->name = $request->name;
        $v->counter = $request->counter;
        $v->type = $request->type;
        $v->model = $request->model;
        $v->group_id = $request->group;
        $v->percentage = $request->percentage;
        $v->tax_counter = $request->tax_counter;
        $v->updated_at = now();
        $v->updated_by = Auth::user()->uuid;
        $v->save();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);

        Variables::destroy($id);
    }

    public function datatables(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = true;//$laratrust->can('update-roles');
        $canDelete = true;//$laratrust->can('delete-roles');
        


        $variables = Variables::all();
        
        return Datatables::of($variables)
        ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

            $string = '<div class="btn-group">';
            if ($canUpdate) {
                $string .= '<a href="'.route('payroll.variables.edit',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
            }
            if ($canDelete) {
                $string .= '&nbsp;&nbsp;<a href="'.route('payroll.variables.destroy',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
            }
            $string .= '</div>';
            return
                $string;
        })
        ->editColumn('type', function ($model) {

            if ($model->type == 1) {
                $string = '<span class="right badge badge-primary">Allowance</span>';
            } elseif ($model->type == 2) {
                $string = '<span class="right badge badge-danger">Deduction</span>';
            } elseif ($model->type == 3) {
                $string = '<span class="right badge badge-primary">Allowance Irregular</span>';
            } elseif ($model->type == 4) {
                $string = '<span class="right badge badge-danger">Deduction Irregular</span>';
            } else {
                $string = '';
            }
            return $string;
        })
        ->editColumn('counter', function ($model) {

            if ($model->counter == 1) {
                $string = 'Per Day';
            } elseif ($model->counter == 2) {
                $string = 'Per Month';
            } else {
                $string = '';
            }
            return $string;
        })
        ->editColumn('tax_counter', function ($model) {
            
            switch ($model->tax_counter) {
                case 0:
                    $res = 'No';
                break;
                case 1:
                    $res = 'Yes';
                break;
                
                default:
                    $res = 'Yes';
                break;
            }

            return $res;
        })
        ->editColumn('model', function ($model) {

            if ($model->model == 1) {
                $string = '<span class="right badge badge-info">Nominal</span>';
            } elseif ($model->model == 2) {
                $string = '<span class="right badge badge-success">Percentage (%)</span>';
            } else {
                $string = '';
            }
            return $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action','type','model'])
        ->make(true);
    }

    public function group()
    {
        $group = Group::all();
        foreach ($group as $key => $value) {
            $data['model'][$key] = $value;
        }

        return view('payroll.variable.formBpjsJamsostek',$data);
    }    

    public function storeGroup(Request $request)
    {
        $model1 = Group::find(1);
        $model1->name = $request->bpjsName;
        $model1->max = $request->bpjsMax;
        $model1->min = $request->bpjsMin;
        $model1->save();

        $model2 = Group::find(2);
        $model2->name = $request->tkName;
        $model2->max = $request->tkMax;
        $model2->min = $request->tkMin;
        $model2->save();
    }

}
