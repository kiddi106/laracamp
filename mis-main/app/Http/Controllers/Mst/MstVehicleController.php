<?php

namespace App\Http\Controllers\Mst;

use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\Employee;
use App\Services\MstVehicle;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Laratrust\Laratrust;

class MstVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['vehicles'] = MstVehicle::getVehicles();
        $data['VehiclesDrivers'] = MstVehicle::getVehiclesDrivers();
        return view('mst.vehicles.list', $data);
    }

    public function list()
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-mst-vehicle');
        $canDelete = $laratrust->can('delete-mst-vehicle');
        

        $laratrust->user()->roles;
        return Datatables::of(MstVehicle::getVehiclesDrivers())
        ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

            $string = '<div class="btn-group">';
            if ($canUpdate) {
                $string .= '<a href="'.route('mst.vehicle.edit',['id' => base64_encode($model->vehicle_id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
            }
            if ($canDelete) {
                $string .= '&nbsp;&nbsp;<a href="'.route('mst.vehicle.remove',['id' => base64_encode($model->vehicle_id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
            }
            $string .= '</div>';
            return
                $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function listDriver()
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-mst-vehicle');
        $canDelete = $laratrust->can('delete-mst-vehicle');
        

        $laratrust->user()->roles;
        return Datatables::of(MstVehicle::getVehiclesDrivers())
        ->editColumn('vehicle', function ($model) {
            $vehicle = $model->vehicle_license.' - '.$model->vehicle_type;
            return $vehicle;
        })
        ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

            $string = '<div class="btn-group">';
            if ($canUpdate) {
                $string .= '<a href="'.route('mst.vehicle.editDriver',['id' => base64_encode($model->driver_id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
            }
            if ($canDelete) {
                $string .= '&nbsp;&nbsp;<a href="'.route('mst.vehicle.removeDriver',['id' => base64_encode($model->driver_id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
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
        // $this->middleware('permission:create-menu');

        $data['vehicles'] = MstVehicle::getVehicles();
        $data['drivers'] = Employee::whereRoleIs('Drv')->get();
        return view('mst.vehicles.create',$data);
    }

    public function getDriver(Request $request)
    {
         $id = \base64_decode($request->id);
         $driver = Employee::find($id);
         return response()->json($driver);
    }

    public function store(Request $request)
    {
        $data['insert']['vehicle'] = $request->vehicle;
        $data['vehicle']['vehicle_license'] = $request->vehicle_license;
        $data['vehicle']['vehicle_type'] = $request->vehicle_type;
        $data['vehicle']['vehicle_color'] = $request->vehicle_color;
        $data['vehicle']['max_passenger'] = $request->max_passenger;
        $data['vehicle']['created_by'] = Auth::user()->uuid;
        $data['vehicle']['created_at'] = date('Y-m-d H:i:s');
        $data['insert']['driver'] =  \base64_decode($request->driver);
        MstVehicle::insert($data);
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

    public function edit($id)
    {
        $this->middleware('permission:update-mst-vehicle');

        $id = \base64_decode($id);

        $data['vehicle'] = MstVehicle::getVehicle($id);
        $data['drivers'] = Employee::whereRoleIs('Drv')->get();

        return view('mst.vehicles.edit',$data);
    }

    public function editDriver($id)
    {
        $this->middleware('permission:update-mst-vehicle');

        $id = \base64_decode($id);

        $data['driver'] = MstVehicle::getDriver($id);
        $data['drivers'] = MstVehicle::getDrivers();

        // dd($data['menu']);

        return view('mst.vehicles.editDriver',$data);
    }


    public function update(Request $request)
    {
        $id = \base64_decode($request->vehicle_id);
        $data['update']['vehicle_license']  = $request->vehicle_license;
        $data['update']['vehicle_type']     = $request->vehicle_type;
        $data['update']['vehicle_color']    = $request->vehicle_color;
        $data['update']['max_passenger']    = $request->max_passenger;
        $data['update_driver']['driver']    = \base64_decode($request->driver);
        $data['update']['updated_by']       = Auth::user()->uuid;
        $data['update']['updated_at']       = date('Y-m-d H:i:s');
        $data['vehicle']['vehicle_id']      = $id;
        $data['where']['vehicle_id']        = $id;

        MstVehicle::update($data);
    }

    public function updateDriver(Request $request)
    {
        $id = \base64_decode($request->driver_id);
        $data['update']['driver_name']   = $request->driver_name;
        $data['update']['driver_age']    = $request->driver_age;
        $data['update']['driver_phone']  = $request->driver_phone;
        $data['update']['updated_by']    = Auth::user()->uuid;
        $data['update']['updated_at']    = date('Y-m-d H:i:s');

        $data['where']['driver_id']        = $id;

        MstVehicle::updateDriver($data);
    }

    public function remove($id)
    {
        $id = \base64_decode($id);

        MstVehicle::remove($id);
    }

    public function removeDriver($id)
    {
        $id = \base64_decode($id);

        MstVehicle::removeDriver($id);
    }
}
