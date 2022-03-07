<?php

namespace App\Http\Controllers\Ga;

use App\Services\MstVehicle;
use App\Services\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\Department;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\Employee;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\Laratrust;

class VehicleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home($tab_id)
    {
        $laratrust = new Laratrust(app());
        // dd($laratrust->user()->roles[0]->display_name);
        $data['tab_id'] = $tab_id;
        $data['vehicles'] = MstVehicle::getVehicles();
        $data['role_driver'] = $laratrust->user()->roles;
        return view('ga.vehicle.home', $data);
    }   
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       // $this->middleware('permission:create-menu');
       $laratrust = new Laratrust(app());
       $data['dept'] = $laratrust->user()->roles[0]->display_name;
       $data['vehicles'] = MstVehicle::getVehicles();
       $data['drivers'] = MstVehicle::getDrivers();
       return view('ga.vehicle.newRequest',$data);
   }

   public function list(Request $request)
   {
       $laratrust = new Laratrust(app());
       $canSchedule = $laratrust->can('schedule-req-vehicle');
       $canUpdate = $laratrust->can('update-req-vehicle');
       $canDelete = $laratrust->can('delete-req-vehicle');
       
       $queryReqVehicle = Vehicle::getReqVehicles();

       if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqVehicle->where('a.req_date', '=', $date_full);
       }
       if ($request->vehicle != "null") {
            $queryReqVehicle->where('a.vehicle_id', '=', \base64_decode($request->vehicle));
       }

       if ($request->status != "null") {
            $queryReqVehicle->where('a.req_vehicle_cd', '=', \base64_decode($request->status));
       }
       $laratrust->user()->roles;
       return Datatables::of($queryReqVehicle)
            ->editColumn('departement', function ($model) {
                $empl = Employee::find($model->employee_uuid);
                $dept = Department::find($empl->roles[0]->department_code);
                $dept = $dept->name;
                return $dept;
            })
            ->editColumn('ext', function ($model) {
                $empl = Employee::find($model->employee_uuid);
                return $empl->ext_no;
            })
            ->editColumn('req_name', function ($model) {
                $empl = Employee::find($model->employee_uuid);
                return $empl->name;
            })
            ->editColumn('vehicle', function ($model) {
                return $model->vehicle_license. ' - '. $model->vehicle_type;
            })
            ->editColumn('departure_tm', function ($model) {
                $departure_tm = ($model->departure_tm != '') ? date_create($model->departure_tm) : '';
                $format = ($departure_tm != '') ? date_format($departure_tm,"H:i") : '-';
                return $format;
            })
            ->editColumn('arrives_tm', function ($model) {
                $arrives_tm = ($model->arrives_tm != '') ? date_create($model->arrives_tm) : '';
                $format = ($arrives_tm != '') ? date_format($arrives_tm,"H:i") : '-';
                return $format;
            })
            ->editColumn('vehicle', function ($model) {
                return $model->vehicle_license. ' - '. $model->vehicle_type;
            })
            ->editColumn('status', function ($model) {
                $status = '';
                if ($model->req_vehicle_cd == 'R') {
                    $status = 'Requested';
                }
                else if ($model->req_vehicle_cd == 'S') {
                    $status = 'Scheduled';
                }
                else if ($model->req_vehicle_cd == 'C') {
                    $status = 'Cancelled';
                }
                return $status;
            })
            ->addColumn('action', function ($model) use ($canSchedule, $canUpdate, $canDelete) {
                $string = '<div class="btn-group">';
                if ($canSchedule) {
                    if ($model->req_vehicle_cd == 'R') {
                        $string .= '<a href="'.route('ga.vehicle.set.schedule',['id' => base64_encode($model->req_vehicle_id)]).'" type="button" class="btn btn-xs btn-success modal-show edit" title="Set Schedule Vehicle"><i class="fas fa-calendar-check"></i></a>';
                    }
                    else if ($model->req_vehicle_cd == 'S') {
                        $string .= '';
                    }
                    else if ($model->req_vehicle_cd == 'C') {
                        $string .= '';
                    }
                }
                if ($canUpdate) {
                    if ($model->req_vehicle_cd == 'R') {
                        $string .= '&nbsp;&nbsp;<a href="'.route('ga.vehicle.edit',['id' => base64_encode($model->req_vehicle_id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit Request Vehicle"><i class="fa fa-edit"></i></a>';
                    }
                    else if ($model->req_vehicle_cd == 'S') {
                        $string .= '';
                    }
                    else if ($model->req_vehicle_cd == 'C') {
                        $string .= '';
                    }
                }
                if ($canDelete) {
                    if ($model->req_vehicle_cd == 'R') {
                        $string .= '&nbsp;&nbsp;<a href="'.route('ga.vehicle.remove',['id' => base64_encode($model->req_vehicle_id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                    }
                    else if ($model->req_vehicle_cd == 'S') {
                        $string .= '&nbsp;&nbsp;<a href="'.route('ga.vehicle.remove',['id' => base64_encode($model->req_vehicle_id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                    }
                    else if ($model->req_vehicle_cd == 'C') {
                        $string .= '';
                    }
                }
                $string .= '</div>';
                return
                    $string;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
   }

   public function listDriver(Request $request)
   {
       $laratrust = new Laratrust(app());
       
       $queryReqVehicle = Vehicle::getReqVehiclesDriver();

       if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqVehicle->where('a.req_date', '=', $date_full);
       }
       if ($request->vehicle != "null") {
            $queryReqVehicle->where('a.vehicle_id', '=', \base64_decode($request->vehicle));
       }
       $laratrust->user()->roles;
       return Datatables::of($queryReqVehicle)
            ->editColumn('departement', function ($model) {
                $empl = Employee::find($model->employee_uuid);
                $dept = Department::find($empl->roles[0]->department_code);
                $dept = $dept->name;
                return $dept;
            })
            ->editColumn('ext', function ($model) {
                $empl = Employee::find($model->employee_uuid);
                return $empl->ext_no;
            })
            ->editColumn('req_name', function ($model) {
                $empl = Employee::find($model->employee_uuid);
                return $empl->name;
            })
            ->editColumn('vehicle', function ($model) {
                return $model->vehicle_license. ' - '. $model->vehicle_type;
            })
            ->editColumn('departure_tm', function ($model) {
                $departure_tm = ($model->departure_tm != '') ? date_create($model->departure_tm) : '';
                $format = ($departure_tm != '') ? date_format($departure_tm,"H:i") : '-';
                return $format;
            })
            ->editColumn('arrives_tm', function ($model) {
                $arrives_tm = ($model->arrives_tm != '') ? date_create($model->arrives_tm) : '';
                $format = ($arrives_tm != '') ? date_format($arrives_tm,"H:i") : '-';
                return $format;
            })
            ->editColumn('vehicle', function ($model) {
                return $model->vehicle_license. ' - '. $model->vehicle_type;
            })
            ->editColumn('status', function ($model) {
                $status = '';
                if ($model->req_vehicle_cd == 'R') {
                    $status = 'Requested';
                }
                else if ($model->req_vehicle_cd == 'S') {
                    $status = 'Scheduled';
                }
                else if ($model->req_vehicle_cd == 'C') {
                    $status = 'Cancelled';
                }
                return $status;
            })
            ->addIndexColumn()
            ->make(true);
   }

   public function listAdmin()
   {
       $laratrust = new Laratrust(app());
       $canUpdate = $laratrust->can('update-menu');
       $canDelete = $laratrust->can('delete-menu');
       

       dd($laratrust->user()->roles);
       return Datatables::of(Vehicle::getReqVehicles())
            ->editColumn('departement', 'MBPS')
            ->editColumn('ext', '0000')
            ->editColumn('driver', '')
            ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

                $string = '<div class="btn-group">';
                if ($canUpdate) {
                    $string .= '<a href="'.route('ga.vehicle.set.schedule',['id' => base64_encode($model->req_vehicle_id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if ($canDelete) {
                    $string .= '&nbsp;&nbsp;<a href="'.route('mstVehicle.removeDriver',['id' => base64_encode($model->req_vehicle_id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                }
                $string .= '</div>';
                return
                    $string;
            })
       ->addIndexColumn()
       ->rawColumns(['action'])
       ->make(true);
   }

   public function store(Request $request)
   {
        $this->validate($request, [
            'purpose' => 'required',
            'destination' => 'required',
        ]);

        $date = $request->date;
        $date = explode('/', $date);
        $data['insert']['employee_uuid'] = \base64_decode($request->empl_id);
        $data['insert']['created_at'] = $request->req_time;
        $data['insert']['created_by'] = $request->req_name;
        $data['insert']['purpose'] = $request->purpose;
        $data['insert']['destination'] = $request->destination;
        $data['insert']['req_date'] = $date[2].'-'.$date[1].'-'.$date[0];
        $data['insert']['departure_tm'] = $request->departure_time;
        $data['insert']['arrives_tm'] = $request->arrives_time;
        $data['insert']['passenger'] = $request->passenger;
        $data['insert']['goods'] = $request->goods;
        $data['insert']['req_vehicle_cd'] = 'R';
        $data['insert']['goods_note'] = ($request->goods == 'Y') ? $request->goods_note : '';
        
        Vehicle::insert($data);
   }
   
   public function edit($id)
   {
        $this->middleware('permission:update-menu');

        $id = \base64_decode($id);

        $data['reqVehicle'] = Vehicle::getReqVehicle($id);
        $data['empl'] = Employee::find($data['reqVehicle']->employee_uuid);
        $data['dept'] = $data['empl']->roles[0]->display_name;
        return view('ga.vehicle.editRequest',$data);
   }
   
   public function update(Request $request)
   {
        $id = \base64_decode($request->req_vehicle_id);
        
        $this->validate($request, [
            'purpose' => 'required',
            'destination' => 'required',
        ]);

        $date = $request->date;
        $date = explode('/', $date);
        $data['update']['employee_uuid'] = \base64_decode($request->empl_id);
        $data['update']['purpose'] = $request->purpose;
        $data['update']['destination'] = $request->destination;
        $data['update']['departure_tm'] = $request->departure_time;
        $data['update']['arrives_tm'] = $request->arrives_time;
        $data['update']['passenger'] = $request->passenger;
        $data['update']['goods'] = $request->goods;
        $data['update']['goods_note'] = ($request->goods == 'Y') ? $request->goods_note : '';
        $data['update']['updated_by']       = Auth::user()->uuid;
        $data['update']['updated_at']       = date('Y-m-d H:i:s');

        $data['where']['req_vehicle_id']        = $id;
        Vehicle::update($data);
   }

   public function setSchedule($id)
   {
        $this->middleware('permission:update-menu');

        $id = \base64_decode($id);

        $data['vehicle'] = MstVehicle::getVehicle($id);
        $data['reqVehicle'] = Vehicle::getReqVehicle($id);
        $data['vehicles'] = MstVehicle::getVehicles();
        $data['drivers'] = Employee::whereRoleIs('Drv')->get();
        
        $data['empl'] = Employee::find($data['reqVehicle']->employee_uuid);
        $data['dept'] = $data['empl']->roles[0]->display_name;
        // dd($data['menu']);

        return view('ga.vehicle.setSchedule',$data);
   }
   
   public function getDriver(Request $request)
   {
        $id = \base64_decode($request->id);
        $driver = Vehicle::getDriver($id);
        return response()->json($driver);
   }

   public function storeSchedule(Request $request)
   {
       
        $this->validate($request, [
            'vehicle' => 'required',
        ]);
        $id = \base64_decode($request->req_vehicle);
        $data['update']['vehicle_id']  = \base64_decode($request->vehicle);
        $data['update']['driver_id']  = $request->driver;
        $data['update']['notes']     = $request->notes;
        $data['update']['req_vehicle_cd']     = 'S';
        $data['update']['updated_by']       = Auth::user()->uuid;
        $data['update']['updated_at']       = date('Y-m-d H:i:s');

        $data['where']['req_vehicle_id']        = $id;

        Vehicle::update($data);
   }

   public function remove($id)
   {
       $id = \base64_decode($id);

       Vehicle::remove($id);
   }
}
