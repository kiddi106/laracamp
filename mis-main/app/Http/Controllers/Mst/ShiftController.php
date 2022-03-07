<?php

namespace App\Http\Controllers\Mst;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mst\Shift;
use App\Models\Mst\EmployeeShift;
use App\Models\Auth\Attendance;
use App\Models\Auth\Department;
use App\Models\Auth\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mst.shift.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mst.shift.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shift = new \App\Models\Mst\Shift();
        $shift->shift_cd = $request->shift_cd;
        $shift->shift_nm = $request->shift_nm;
        $shift->sched_in = $request->sched_in;
        $shift->sched_out = $request->sched_out;
        $shift->created_at = date('Y-m-d H:i:s');
        $shift->created_by = Auth::user()->uuid;
        $shift->save();
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
    }

    public function destroy($id)
    {
        //
    }

    public function dataTables(Request $request)
    {
        return DataTables::of(Shift::all())
        ->addIndexColumn()
        ->addColumn('action', function ($model) {
            // return '<a href="'.route('mst.employee.show',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-secondary btn-show" title="Show"><i class="fa fa-eye"></i> show</a>';
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

    public function set()
    {
        $data['departments'] = Department::all();
        $data['roles'] = Role::all();

        // dd($tanggal);

        return view('mst.shift.set',$data);
    }
    function tanggal($date)
    {
        $exp = explode('-',$date);
        $date = $exp[2].'/'.$exp[1].'/'.$exp[0];
        return $date;
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

            $data = EmployeeShift::where('employee_uuid','=',$emp)->where('date','=',$tanggal)->get();

            if (count($data) > 0) {
                $dates[$key]['shift'] =$data[0]->shift_cd;
            } else {
                $dates[$key]['shift'] = '';
            }

            $dates[$key]['tanggal'] = date( $format, $current );
            $current = strtotime( $step, $current );
            $key ++;

        }
        $shifts = Shift::all();
    
        return view('mst.shift.tableSet',['dateRange' => $dates,'shifts'=>$shifts])->render();
    }

    public function setShift(Request $request)
    {
        $tanggal = $request->tanggal;
        $employee_uuid = $request->employee_uuid;
        $shift_cd = $request->shift_cd;
        $exp = explode('/',$tanggal);
        $tanggal = $exp[2].'-'.$exp[1].'-'.$exp[0];

        $besok = date('Y-m-d',(strtotime ( '+1 day' , strtotime ( $tanggal) ) ));
        $kemarin = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $tanggal) ) ));

        if ($shift_cd == 'SHF-1' || $shift_cd == 'OPT-1') 
        {
            $date_in = $kemarin;
            $date_out = $tanggal;
        }
        elseif ($shift_cd == 'SHF-3' || $shift_cd == 'OPT-3') 
        {
            $date_in = $tanggal;
            $date_out = $besok;
        }
        else
        {
            $date_in = $tanggal;
            $date_out = $tanggal;
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
                $EmolyeeShift->shift_cd = $shift_cd;
                $EmolyeeShift->date_in = $date_in;
                $EmolyeeShift->date_out = $date_out;
                $EmolyeeShift->save();
                $response = 'success';
            }

        } 
        else 
        {
            $EmolyeeShift = new \App\Models\Mst\EmployeeShift();
            $EmolyeeShift->employee_uuid = $employee_uuid;
            $EmolyeeShift->date = $tanggal;
            $EmolyeeShift->date_in = $date_in;
            $EmolyeeShift->date_out = $date_out;
            $EmolyeeShift->shift_cd = $shift_cd;
    
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
}
