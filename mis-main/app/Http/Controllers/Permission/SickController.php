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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tanggals = Attendance::leftjoin("mst_shift as ms","attendances.shift_cd","=","ms.shift_cd")
        ->whereRaw("[employee_uuid] = '".Auth::user()->uuid."' and date BETWEEN DATEADD(DAY, -60, GETDATE()) AND GETDATE()")
        ->select("date","time_in","time_out",'sched_in',"sched_out")
        ->get();

        foreach ($tanggals as $key => $value) {
            $date[] = date('Y-n-j',strtotime($value['date']));
        }

        $permission = Permission::join("req_permission_dtl as rpd","req_permission.permission_id","=","rpd.permission_id")
        ->whereRaw("[employee_uuid] = '".Auth::user()->uuid."' and rpd.permission_date BETWEEN  DATEADD(DAY, -730, DATEADD(yy, DATEDIFF(yy, 0, GETDATE()) + 1, -1)) AND  DATEADD(DAY, +730, DATEADD(yy, DATEDIFF(yy, 0, GETDATE()) + 1, -1)) and status_id = 2")->get();

        if (count($permission) > 0) {
            foreach ($permission as $key => $value) {

                $permissionDate[] = date('Y-n-j',strtotime($value['permission_date']));
            }

            $sum = array_merge($permissionDate,$date);
        }
        else
        {
            $sum = $date;
        }

        foreach ($sum as $key => $value) {
            $enable[] = $value;
        }

        $data['date'] = json_encode($enable);
        $data['type'] = TypePermission::where("category_permission_cd","=","SICK")->get();
        return view('permission.sick.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['type'] = TypePermission::where("category_permission_cd","=","SICK")->get();
        return view('permission.sick.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permission_date = explode(",",$request->permission_date);


        $permission = new \App\Models\Permission\Permission();
        $permission_id = $this->permission_id();

        $permission->employee_uuid = Auth::user()->uuid;
        $permission->permission_id = $permission_id;
        $permission->req_date = date('Y-m-d');
        $permission->type_permission_cd = $request->type;
        $permission->status_id = 1;
        $permission->note = $request->note;
        $permission->created_at = date('Y-m-d H:i:s');
        $permission->created_by = Auth::user()->uuid;

        //Upload File
        $request->hasFile('file'); 
        $files = $request->file('file');

        if ($files) {
            $hashName = $files->hashName();
            $folderName = 'Permission/Sick';
            $fileName = $hashName;
            $file = storage_path().'/app/Permission/Sick/'.$hashName;
            if (file_exists($file)) {
                Storage::delete($folderName . '/' . $fileName);
            }
            $files->store($folderName);
            // Storage::move($folderName . '/' . $hashName, $folderName . '/' . $fileName);
                
            // Storage::move('uploads/'.$filename, $file);
            $permission->file_upload = $hashName;
        }

        $permission->save();



        if ($permission->save()) {
            $permission_date = explode(",",$request->permission_date);

            foreach ($permission_date as $key => $value) {
                $dtl = new \App\Models\Permission\DtlPermission();
                $dtl->permission_id = $permission_id;
                $dtl->permission_date = $this->tanggalDb($value);
                // $dtl->created_at = date('Y-m-d H:i:s');
                $dtl->created_by = Auth::user()->uuid;
                $dtl->save();

            }
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
        $permission_id = base64_decode($id);
        $data['permission'] = Permission::join('mst_type_permission as mtp','req_permission.type_permission_cd','=','mtp.type_permission_cd')->where('permission_id','=',$permission_id)->select('req_permission.*','mtp.*')->get();
        $data['dtl'] = DtlPermission::where("permission_id","=",$permission_id)->get();


        return view('permission.sick.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function notes($type,$id)
    {
        $data['permission_id'] = $id;
        $data['type'] = $type;
        return view('permission.sick.notes',$data);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function cancel(Request $request)
    {
        $permission_id = base64_decode($request->permission_id);
        $permission = Permission::findOrFail($permission_id);

        $permission->status_id = 3;
        $permission->cancel_reason = $request->note;
        $permission->updated_at = date('Y-m-d H:i:s');
        $permission->updated_by = Auth::user()->uuid;
        $permission->save();
    }

    public function approve($id)
    {
        $permission_id = base64_decode($id);
        $permission = Permission::findOrFail($permission_id);

        $permission->status_id = 2;
        $permission->updated_at = date('Y-m-d H:i:s');
        $permission->updated_by = Auth::user()->uuid;
        $permission->save();
    }

    public function reject(Request $request)
    {
        $permission_id = base64_decode($request->permission_id);
        $permission = Permission::findOrFail($permission_id);

        $permission->status_id = 4;
        $permission->reject_reason = $request->note;
        $permission->updated_at = date('Y-m-d H:i:s');
        $permission->updated_by = Auth::user()->uuid;
        $permission->save();
    }

    public function dataTables(Request $request)
    {

        $date = explode(' - ', $request->date);
        $start = $this->tanggalDb($date[0]);
        $end = $this->tanggalDb($date[1]);

        $laratrust = new Laratrust(app());
        $canApprove = $laratrust->can('approve-permission');
        $role = $laratrust->user()->roles;
        $query = Employee::employeePermissions($role[0]->id,"SICK",$canApprove,Auth::user()->uuid,$start,$end);

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('action', function ($model) use ($canApprove) {
            $button = '<a href="'.route('permission.sick.show',['id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-xs btn-secondary btn-show" title="Detail Permission"><i class="fa fa-eye"></i> Detail</a> &nbsp';

            if ($model->status_id == 1) 
            {
                if ($model->employee_uuid != Auth::user()->uuid) {
                    if ($canApprove) {
                        $button .= '<a href="'.route('permission.sick.approve',['id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-xs btn-success btn-approve" title=""><i class="fa fa-check"></i> Approve</a> &nbsp';
                        $button .= '<a href="'.route('permission.sick.notes',['type'=>'reject','id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-xs btn-warning btn-show" title="Reject Permission"><i class="fa fa-times"></i> Reject</a>';
                    }
                }
                else
                {
                    $button .= '<a href="'.route('permission.sick.notes',['type' => 'cancel','id' => base64_encode($model->permission_id)]).'" type="button" class="btn btn-danger btn-xs btn-show" title="Cancel Permission"><i class="fa fa-times"></i> Cancel</a> &nbsp';
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
        ->where("mtp.category_permission_cd","=","SICK")
        ->whereRaw("format(req_date,'yyyy') = ? ",[$tahun])
        ->latest('permission_id')
        ->select("req_permission.*","mtp.type_permission_name as type_permission")->first();
        $last_no =(!is_null($nomor)) ? $nomor->permission_id : "SIL".$tahun.$bulan.$hari."00000";
        $explode = substr($last_no,-5);
        
        $no= "SIL".$tahun.$bulan.$hari.sprintf('%05d',$explode+1);
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

    public function downloadFile($name)
    {
        $path = storage_path().'/app/Permission/Sick/'.$name;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }
}
