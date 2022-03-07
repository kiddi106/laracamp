<?php

namespace App\Http\Controllers\Er;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Er\Shift;
use App\Models\Er\Location;
use App\Models\Er\Project;
use App\Models\Er\Role;
use App\Models\Er\Employee;
use App\Models\Er\Company;
use App\Models\Er\EmployeeShift;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProjectShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['companies'] = Company::all();

        return view('er.projectShift.index',$data);
    }

    public function set()
    {
        $data['departments'] = Project::all();
        $data['roles'] = Role::all();
        $data['location'] = Location::all();

        return view('er.projectShift.set',$data);
    }

    public function location()
    {
        $data['companies'] = Company::all();

        return view('er.projectShift.location',$data);
    }


    public function getRoles(Request $request)
    {
        $department_code = $request->department_code;
        $roles = Role::where('department_code', '=', $department_code)->get();
        return response()->json($roles);
    }

    public function getEmployees(Request $request)
    {
        $role_id = $request->role_id;
        $roles = Employee::join('MESDB.dbo.role_employee as re','re.employee_uuid','=','employees.uuid')
                        ->where('re.role_id',$role_id)
                        ->get();

        return response()->json($roles);
    }

    function dateRange(Request $request) {
        $emp = $request->emp;
        $date = explode(' to ',$request->date);
        $first = $this->tanggal($date[0]);
        $last = $this->tanggal($date[1]);

        $step = '+1 day';
        $format = 'd/m/Y'; 
        $dates = array();
        $current = strtotime( $first );
        $last = strtotime( $last );
        $key = 0;
    
        while( $current <= $last ) {
            $tanggal = date( $format, $current );
            $exp = explode('/',$tanggal);
            $tanggal = $exp[2].'-'.$exp[1].'-'.$exp[0];

            $data = EmployeeShift::join('MESDB.dbo.mst_shift as ms','ms.id','=','employee_shift.shift_id')
                ->where('employee_uuid','=',$emp)->where('date','=',$tanggal)->get();

            $data_loc = EmployeeShift::join('MESDB.dbo.mst_location as ml','ml.id','=','employee_shift.location_id')
            ->where('employee_uuid','=',$emp)->where('date','=',$tanggal)->get();

            if (count($data) > 0) {
                $dates[$key]['shift'] =$data[0]->shift_nm;
            } else {
                $dates[$key]['shift'] = '';
            }

            if (count($data_loc) > 0) {
                $dates[$key]['location'] =$data_loc[0]->name;
            } else {
                $dates[$key]['location'] = '';
            }


            $dates[$key]['tanggal'] = date( $format, $current );
            $current = strtotime( $step, $current );
            $key ++;

        }
        $empl = Employee::where('uuid',$emp)->get();
        $shifts = Shift::where('company_id',$empl[0]->company_id)->get();
        $locations = Location::where('department_code',$request->department)->get();
    
        return view('er.projectShift.tableSet',['dateRange' => $dates,'shifts'=>$shifts, 'locations'=>$locations])->render();
    }

    function tanggal($date)
    {
        $exp = explode('-',$date);
        $date = $exp[2].'/'.$exp[1].'/'.$exp[0];
        return $date;
    }

    public function setShift(Request $request)
    {
        $tanggal = $request->tanggal;
        $employee_uuid = $request->employee_uuid;
        $shift_cd = $request->shift_cd;
        $loc_cd = $request->loc_cd;
        $department_code = $request->project_code;
        $exp = explode('/',$tanggal);
        $tanggal = $exp[2].'-'.$exp[1].'-'.$exp[0];

        $besok = date('Y-m-d',(strtotime ( '+1 day' , strtotime ( $tanggal) ) ));
        $kemarin = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $tanggal) ) ));

        if ($shift_cd && $shift_cd != 'off') 
        {

            $shift = Shift::where('id',$shift_cd)->get();
            if ($shift[0]->allow_before) {
                $date_in = $kemarin;
            }
            else
            {
                $date_in = $tanggal;
            }
    
            if ($shift[0]->allow_after) {
                $date_out = $besok;
            }
            else
            {
                $date_out = $tanggal;
            }
        }
        


        $check = EmployeeShift::where('employee_uuid','=',$employee_uuid)->where('date','=',$tanggal)->get();
        if (count($check) > 0) 
        {
            if ($shift_cd == 'off') 
            {
                $EmolyeeShift = EmployeeShift::findOrFail($check[0]->id);
               
                $EmolyeeShift->shift_id=null;
                $EmolyeeShift->save();
                $response = 'success';

                $checkShift = EmployeeShift::where('employee_uuid','=',$employee_uuid)->where('date','=',$tanggal)->get();

                if(empty($checkShift[0]->shift_id)&& empty($checkShift[0]->location_id)){
                    $EmolyeeShift = EmployeeShift::findOrFail($check[0]->id);
                    $EmolyeeShift->forceDelete();
                    $response = 'success';
                }

            }
            else if ($loc_cd == 'off')
            {
                $EmolyeeShift = EmployeeShift::findOrFail($check[0]->id);
                
                $EmolyeeShift->location_id=null;
                $EmolyeeShift->save();
                $response = 'success';

                $checkShift = EmployeeShift::where('employee_uuid','=',$employee_uuid)->where('date','=',$tanggal)->get();

                if(empty($checkShift[0]->shift_id)&& empty($checkShift[0]->location_id)){
                    $EmolyeeShift = EmployeeShift::findOrFail($check[0]->id);
                    $EmolyeeShift->forceDelete();
                    $response = 'success';
                }

            }

            else {
                $EmolyeeShift = EmployeeShift::findOrFail($check[0]->id);

                if ($shift_cd ) {

                    $EmolyeeShift->shift_id = $shift_cd;
                    
                }

                if ($loc_cd ) {
                    
                    $date_in = $check[0]->date_in;
                    $date_out = $check[0]->date_out;
                    $EmolyeeShift->location_id = $loc_cd;
                }
                $EmolyeeShift->date_in = $date_in;
                $EmolyeeShift->date_out = $date_out;
                $EmolyeeShift->department_code = $department_code;
                $EmolyeeShift->save();
                $response = 'success';
            }

        } 
        else 
        {

            $EmolyeeShift = new \App\Models\Er\EmployeeShift();

            if ($shift_cd ) {

                $EmolyeeShift->shift_id = $shift_cd;
                
            }

            if ($loc_cd ) {
                
                $date_in=$tanggal;
                $date_out=$tanggal;
                $EmolyeeShift->location_id = $loc_cd;
            }
    
            $EmolyeeShift->employee_uuid = $employee_uuid;
            $EmolyeeShift->date = $tanggal;
            $EmolyeeShift->date_in = $date_in;
            $EmolyeeShift->department_code = $department_code;
            $EmolyeeShift->date_out = $date_out;

            
            $EmolyeeShift->save();
            if ($EmolyeeShift->save()) {
                $response = 'success';
            }
            else
            {
                $response = 'failed';
            }
        }
        return $response;
    }


    public function setLocation(Request $request)
    {
        $tanggal = $request->tanggal;
        $employee_uuid = $request->employee_uuid;
        $loc_cd = $request->loc_cd;
        $department_code = $request->project_code;
        $exp = explode('/',$tanggal);
        $tanggal = $exp[2].'-'.$exp[1].'-'.$exp[0];

        $besok = date('Y-m-d',(strtotime ( '+1 day' , strtotime ( $tanggal) ) ));
        $kemarin = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $tanggal) ) ));

        if ($shift_cd != 'off') 
        {
            $shift = Shift::where('id',$shift_cd)->get();
            if ($shift[0]->allow_before) {
                $date_in = $kemarin;
            }
            else
            {
                $date_in = $tanggal;
            }
    
            if ($shift[0]->allow_after) {
                $date_out = $besok;
            }
            else
            {
                $date_out = $tanggal;
            }
        }
        


        $check = EmployeeShift::where('employee_uuid','=',$employee_uuid)->where('date','=',$tanggal)->get();
        if (count($check) > 0) 
        {
            if ($shift_cd == 'off') 
            {
                $EmolyeeShift = EmployeeShift::findOrFail($check[0]->id);
                $EmolyeeShift->forceDelete();
                $response = 'success';
            }
            else {
                $EmolyeeShift = EmployeeShift::findOrFail($check[0]->id);
                $EmolyeeShift->shift_id = $shift_cd;
                $EmolyeeShift->date_in = $date_in;
                $EmolyeeShift->date_out = $date_out;
                $EmolyeeShift->department_code = $department_code;
                $EmolyeeShift->save();
                $response = 'success';
            }

        } 
        else 
        {
            $EmolyeeShift = new \App\Models\Er\EmployeeShift();
            $EmolyeeShift->employee_uuid = $employee_uuid;
            $EmolyeeShift->date = $tanggal;
            $EmolyeeShift->date_in = $date_in;
            $EmolyeeShift->department_code = $department_code;
            $EmolyeeShift->date_out = $date_out;
            $EmolyeeShift->shift_id = $shift_cd;
            

    
            $EmolyeeShift->save();
            if ($EmolyeeShift->save()) {
                $response = 'success';
            }
            else
            {
                $response = 'failed';
            }
        }
        return $response;
    }


    public function create()
    {
        $data['companies'] = Company::all();

        return view('er.projectShift.create',$data);
    }

    public function create_location()
    {
        $data['departments'] = Project::all();

        return view('er.projectShift.create_location',$data);
    }

    public function store_loc(Request $request)
    {
        $this->validate($request,[
            'department' => 'required',
            'loc_name' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'default_loc' => 'required|in:YES,NO'
         ]);

        $location = new Location();
        $location->department_code = $request->department;
        $location->name = $request->loc_name;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->default = $request->default_loc;
        $location->created_by = Auth::user()->uuid;
        $location->created_at = date('Y-m-d H:i:s');
        $location->save();

        if ($location->save()) {
            if ($request->default_loc == "YES")
            Location::where('department_code', $request->department)
            ->where('id', '<>', $location->id)->update(['default' => "NO"]);
        }
    }


    public function edit_loc($id)
    {
        $id = \base64_decode($id);

        $data['location'] = Location::find($id);
        $data['department'] = Project::get();

        $LocationID = Location::where('id',$id)->get();


        foreach ($LocationID as $key => $value) {
            $data['LocationID'][] = $value->id;
        }

        // dd($data);
        return view('er.projectShift.edit_loc',$data);
    }

    public function update_loc(Request $request)
    {
        $id = \base64_decode($request->id);

        $location = Location::find($id);
        $location->department_code = $request->department;
        $location->name = $request->loc_name;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->default = $request->default_loc;
        
        $location->created_by = Auth::user()->uuid;
        $location->created_at = date('Y-m-d H:i:s');

        $location->save();
        if ($location->save()) {
            if ($request->default_loc == "YES")
            Location::where('department_code', $request->department)
            ->where('id', '<>', $id)->update(['default' => "NO"]);
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

        $shift = new Shift();
        $shift->company_id = $request->company;
        $shift->shift_nm = $request->shift_nm;
        $shift->sched_in = $request->sched_in;
        $shift->sched_out = $request->sched_out;
        $shift->allow_before = $request->allow_before;
        $shift->allow_after = $request->allow_after;
        $shift->created_at = date('Y-m-d H:i:s');
        $shift->created_by = Auth::user()->uuid;
        $shift->save();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dataTable(Request $request)
    {
        $company_id = $request->company;

        return DataTables::of(Shift::where('company_id',$company_id))
        ->addIndexColumn()
        ->addColumn('action', function ($model) {
            // return '<a href="'.route('mst.employee.show',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-secondary btn-show" title="Show"><i class="fa fa-eye"></i> show</a>';
        })
        ->addColumn('company', function ($model) {
            return $model->company->name;
        })
        ->addColumn('in', function ($model) {
            return substr($model->sched_in,0,5);
        })
        ->addColumn('out', function ($model) {
            return substr($model->sched_out,0,5);
        })
        ->rawColumns(['action','in','out'])
        ->make(true);
    }

    public function dataTable_loc(Request $request)
    {
        $department_code = $request->department;
        return DataTables::of(Location::whereHas('department', function(Builder $query) use($department_code) {
            $query->where('company_id', '=', $department_code); }))

        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            $string .= '<a href="'.route('er.project.shift.edit_loc',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit Project Location"><i class="fa fa-edit"></i></a>';
            $string .= '</div>';
            return
                $string;
        })
        ->addIndexColumn()
        ->addColumn('department', function ($model) {
            return $model->department->name;
        })
        ->addColumn('location', function ($model) {
            return $model->name;
        })
        ->addColumn('lat', function ($model) {
            return $model->latitude;
        })
        ->addColumn('long', function ($model) {
            return $model->longitude;
        })
        ->rawColumns(['department','location','lat','long','action'])
        ->make(true);

    }
}
