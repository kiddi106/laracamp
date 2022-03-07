<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission\Permission;
use App\Models\Permission\DtlPermission;
use App\Models\Mst\TypePermission;
use Laratrust\Laratrust;
use App\Services\Employee;
use App\Models\Auth\Attendance;
use DateTime;

class RegularController extends Controller
{
    public function index()
    {
        return view('permission.regular.index');
    }

    public function create()
    {
        $data['type'] = TypePermission::where("category_permission_cd","=","REG-P")->get();

        $tanggals = Attendance::leftjoin("mst_shift as ms","attendances.shift_cd","=","ms.shift_cd")
        ->whereRaw("[employee_uuid] = '".Auth::user()->uuid."' and date BETWEEN DATEADD(DAY, -60, GETDATE()) AND GETDATE()")
        ->select("date","time_in","time_out",'sched_in',"sched_out")
        ->get();

        foreach ($tanggals as $key => $value) {

            if ($value['shift_cd']) {
                $schedIn = strtotime($value['sched_in']);
                $schedOut = strtotime($value['sched_out']);
            }
            else
            {
                $schedIn = strtotime("08:00:00");
                $schedOut = strtotime("17:00:00");
            }


            $timeIn = strtotime($value['time_in']);

            $timeOut = strtotime($value['time_out']);

            
            
            $diffTimeIn = $timeIn - $schedIn;
            $diffMenit    = floor($diffTimeIn / 60);



            if ($diffMenit > 10) 
            {
                $telat[] = date('Y-n-j',strtotime($value['date']));
            }

            if ($timeOut < $schedOut) 
            {
                $early[] = date('Y-n-j',strtotime($value['date']));
            }

            $date[] = date('Y-n-j',strtotime($value['date']));
        }

        $step = '+1 day';
        $format = 'Y-n-j'; 
        $dates = array();
        $current = strtotime( date('Y-m-d', strtotime("-60 days")) );
        $last = strtotime( date("Y-m-d") );
        $key = 0;
    
        while( $current <= $last ) {
            $tanggal = date( $format, $current );

            $dates[$key] = date( $format, $current );
            $current = strtotime( $step, $current );
            $key ++;

        }

        $compare = array_diff($dates,$date);
        $merge = array_merge($telat,$early);

        $sum = array_merge($compare,$merge);

        $permission = Permission::join("req_permission_dtl as rpd","req_permission.permission_id","=","rpd.permission_id")
        ->whereRaw("[employee_uuid] = '".Auth::user()->uuid."' and rpd.permission_date BETWEEN DATEADD(DAY, -60, GETDATE()) AND GETDATE() and status_id = 2")->get();
       
        if (count($permission) > 0) {
            foreach ($permission as $key => $value) {

                $permissionDate[] = date('Y-n-j',strtotime($value['permission_date']));
            }
            $tanggal = array_diff($sum,$permissionDate);
        }
        else
        {
            $tanggal = $sum;
        }


        foreach ($tanggal as $key => $value) {
            $enable[] = $value;
        }

        $data['date'] = json_encode($enable);

        return view('permission.regular.create',$data);
    }

    public function store(Request $request)
    {
        $permission_date = explode(",",$request->permission_date);


        $regular = new \App\Models\Permission\Permission();
        $permission_id = $this->permission_id();

        $regular->employee_uuid = Auth::user()->uuid;
        $regular->permission_id = $permission_id;
        $regular->req_date = date('Y-m-d');
        $regular->type_permission_cd = $request->type;
        $regular->status_id = 1;
        $regular->note = $request->note;
        $regular->created_at = date('Y-m-d H:i:s');
        $regular->created_by = Auth::user()->uuid;
        $regular->save();

        if ($regular->save()) {
            $permission_date = explode(",",$request->permission_date);

            foreach ($permission_date as $key => $value) {
                $dtl = new \App\Models\Permission\DtlPermission();
                $dtl->permission_id = $permission_id;
                $dtl->permission_date = $this->tanggalDb($value);
                // $dtl->created_at = date('Y-m-d H:i:s');
                $dtl->created_by = Auth::user()->uuid;
                $dtl->save();

                echo($dtl->save());

            }
        }

    }


    public function show($id)
    {
        $permission_id = base64_decode($id);
        $data['regular'] = Permission::join('mst_type_permission as mtp','req_permission.type_permission_cd','=','mtp.type_permission_cd')->where('permission_id','=',$permission_id)->select('req_permission.*','mtp.*')->get();
        $data['dtl'] = DtlPermission::where("permission_id","=",$permission_id)->get();


        return view('permission.regular.show',$data);
    }

    public function notes($type,$id)
    {
        $data['permission_id'] = $id;
        $data['type'] = $type;
        return view('permission.regular.notes',$data);
    }

    public function cancel(Request $request)
    {
        $permission_id = base64_decode($request->permission_id);
        $regular = Permission::findOrFail($permission_id);

        $regular->status_id = 3;
        $regular->cancel_reason = $request->note;
        $regular->updated_at = date('Y-m-d H:i:s');
        $regular->updated_by = Auth::user()->uuid;
        $regular->save();
    }

    public function approve($id)
    {
        $permission_id = base64_decode($id);
        $regular = Permission::findOrFail($permission_id);

        $regular->status_id = 2;
        $regular->updated_at = date('Y-m-d H:i:s');
        $regular->updated_by = Auth::user()->uuid;
        $regular->save();

        if ($regular->type_permission_cd == "ATTNR") 
        {
            $dtl = DtlPermission::where("permission_id","=",$permission_id)->get();

            foreach ($dtl as $key => $value) {

                $att = Attendance::whereRaw("employee_uuid = '".Auth::user()->uuid."' and date = '".$value['permission_date']."'")->first();

                if (!$att) {
                    $att = new \App\Models\Auth\Attendance();
                    $att->employee_uuid = $regular->employee_uuid;
                    $att->date = $value['permission_date'];
                    $att->date_in = $value['permission_date'];
                    $att->date_out = $value['permission_date'];
                    $att->time_in = "08:00:00";
                    $att->time_out = "17:00:00";
                    $att->shift_cd = "SHF-R";
                    $att->save();
                }
            }
        }
    }

    public function reject(Request $request)
    {
        $permission_id = base64_decode($request->permission_id);
        $regular = Permission::findOrFail($permission_id);

        $regular->status_id = 4;
        $regular->reject_reason = $request->note;
        $regular->updated_at = date('Y-m-d H:i:s');
        $regular->updated_by = Auth::user()->uuid;
        $regular->save();
    }

    public function dataTables(Request $request)
    {

        $date = explode(' - ', $request->date);
        $start = $this->tanggalDb($date[0]);
        $end = $this->tanggalDb($date[1]);

        $laratrust = new Laratrust(app());
        $canApprove = $laratrust->can('approve-permission');
        $role = $laratrust->user()->roles;
        $query = Employee::employeePermissions($role[0]->id,"REG-P",$canApprove,Auth::user()->uuid,$start,$end);

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('action', function ($model) use ($canApprove) {
            $button = '<a href="'.route('permission.regular.show',['id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-xs btn-secondary btn-show" title="Detail Permission"><i class="fa fa-eye"></i> Detail</a> &nbsp';

            if ($model->status_id == 1) 
            {
                if ($model->employee_uuid != Auth::user()->uuid) {
                    if ($canApprove) {
                        $button .= '<a href="'.route('permission.regular.approve',['id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-xs btn-success btn-approve" title=""><i class="fa fa-check"></i> Approve</a> &nbsp';
                        $button .= '<a href="'.route('permission.regular.notes',['type'=>'reject','id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-xs btn-warning btn-show" title="Reject Permission"><i class="fa fa-times"></i> Reject</a>';
                    }
                }
                else
                {
                    $button .= '<a href="'.route('permission.regular.notes',['type' => 'cancel','id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-danger btn-xs btn-show" title="Cancel Permission"><i class="fa fa-times"></i> Cancel</a> &nbsp';
                }
            }
            return $button;
        })
        ->addColumn('status', function ($model) {
            if ($model->status_id == 1)  
            {
                return '<span class="badge badge-primary">'.$model->status.'</span>';
            } 
            elseif ($model->status_id == 2) {
                return '<span class="badge badge-success">'.$model->status.'</span>';
            } 
            elseif ($model->status_id == 3) {
                return '<span class="badge badge-danger">'.$model->status.'</span>';
            }
            elseif ($model->status_id == 4) {
                return '<span class="badge badge-warning">'.$model->status.'</span>';
            }
            
        })
        ->addColumn('permission_date', function ($model) {
            // return $this->tanggalView($model->permission_date_from).' - '.$this->tanggalView($model->permission_date_to);
        })
        ->addColumn('request_date', function ($model) {
            return $this->tanggalView($model->req_date);

        })
        ->rawColumns(['action','status','request_date'])
        ->make(true);
    }

    public function permission_id(){
    
        $tahun = date ('Y');
        $bulan = date ('m');
        $hari = date ('d');
        $nomor = Permission::join("mst_type_permission as mtp","req_permission.type_permission_cd","=","mtp.type_permission_cd")
        ->where("mtp.category_permission_cd","=","REG-P")
        ->whereRaw("format(req_date,'yyyy') = ? ",[$tahun])
        ->latest('permission_id')
        ->select("req_permission.*","mtp.type_permission_name as type_permission")->first();
        $last_no =(!is_null($nomor)) ? $nomor->permission_id : "PER".$tahun.$bulan.$hari."00000";
        $explode = substr($last_no,-5);
        
        $no= "PER".$tahun.$bulan.$hari.sprintf('%05d',$explode+1);
        return $no;

    }

    function tanggalDb($date)
    {
        $exp = explode('/',$date);
        $date = $exp[2].'-'.$exp[1].'-'.$exp[0];
        return $date;
    }

    function tanggalView($date)
    {
        $exp = explode('-',$date);
        $date = $exp[2].'/'.$exp[1].'/'.$exp[0];
        return $date;
    }
}
