<?php

namespace App\Http\Controllers\Ga;

use App\Http\Controllers\Controller;
use App\Models\Auth\Employee;
use App\Models\Auth\Role;
use App\Models\Mst\BrandAsset;
use App\Models\Mst\CategoryAsset;
use App\Models\Mst\CheckInAsset;
use App\Models\Mst\CheckoutAsset;
use App\Models\Mst\DepartmentAsset;
use App\Models\Mst\EmployeeAsset;
use App\Models\Mst\EmployeeDepartment;
use App\Models\Mst\ModelTypeAsset;
use Illuminate\Http\Request;
use App\Models\Mst\MstAsset;
use App\Models\Mst\TransaksiAsset;
use App\Services\Asset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Str;

use function GuzzleHttp\Promise\all;
use function Psy\debug;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];

        $asset_no = $request->input('asset_no');
        $serial_no = $request->input('serial_no');
        $category_id = $request->input('category_id');
        $status = $request->input('status');
        $brand_id = $request->input('brand_id');
        $modelType_id = $request->input('model_id');
        $startdate_arrived = $request->input('daterangestart_arrived');
        $enddate_arrived = $request->input('daterangeend_arrived');
        $startdate_handover = $request->input('daterangestart_handover');
        $enddate_handover = $request->input('daterangeend_handover');

        $data['search']['asset_no'] = $asset_no;
        $data['search']['serial_no'] = $serial_no;
        $data['search']['category_id'] = $category_id;
        $data['search']['status'] = $status;
        $data['search']['brand_id'] = $brand_id;
        $data['search']['model_id'] = $modelType_id;
        $data['search']['arrived_dt'] = $startdate_arrived . ' - ' . $enddate_arrived;
        $data['search']['handover_dt'] = $startdate_handover . ' - ' . $enddate_handover;

        $pageNumber = $request->input('pageNumber') ?: 1;
        $rowPerPage = $request->input('rowPerPage') ?: config('app.dataTable.display');

        $tlnSrc = Asset::search($pageNumber, $rowPerPage, $asset_no, $serial_no, $category_id, $status, $brand_id, $modelType_id, $startdate_arrived, $enddate_arrived, $startdate_handover, $enddate_handover);
        $data['assets'] = $tlnSrc['assets'];
        $data['paging'] = $tlnSrc['page'];
        // dd($data);
        return view('ga.asset.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstAsset();
        return view('ga.asset.form',  ['model' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'asset_no' => 'required',
            'category_id' => 'required',
            'serial_no' => 'required',
            'brand_id' => 'required',
            'asset_no' => 'required',
            'req_date' => 'required',
            'location' => 'required',
            'arrived_date' => 'required',
        ]);

        $asset = new MstAsset();
        $asset->brand_id = $request->brand_id;
        $asset->category_id = $request->category_id;
        $asset->model_type_id = $request->modeltype_id;
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $hashName = $files->hashName();
            $folderName = public_path() . '/img/ga/asset';
            $fileName = $hashName;
            Storage::disk('asset_image')->put('asset', $files);
            $asset->file_image_nm = $fileName;
        }
        $asset->serial_no = $request->serial_no;
        $asset->asset_no = $request->asset_no;
        $asset->specification = $request->specification;
        $asset->allocation = $request->allocation;
        $asset->city_id = $request->location;
        $asset->request_dt = date('Y-m-d', strtotime($request->req_date));
        $asset->goods_arrived_dt = date('Y-m-d', strtotime($request->arrived_date));

        $asset->note = $request->note;
        $asset->created_by = Auth::user()->uuid;
        if ($asset->save()) {
            $transaksi_asset = new TransaksiAsset();
            $transaksi_asset->asset_id = $asset->id;
            $transaksi_asset->status_id = '1';
            $transaksi_asset->created_by = Auth::user()->uuid;
            if ($transaksi_asset->save()) {
                return redirect()->route('ga.asset.index')->with('alert.success', 'Asset Has Been Saved');
            }
        }
        return redirect()->back()->with('alert.failed', 'Failed Create Asset ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = TransaksiAsset::where('asset_id', '=', $id);
        // $model = $query->first();
        // dd($model->asset->location->loc_nm);
        return view('ga.asset.view', ['model' => $query->first()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MstAsset::findOrFail($id);

        return view('ga.asset.form', ['model' => $model]);
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
        // dd($request->input());
        $this->validate($request, [
            'asset_no' => 'required',
            'category_id' => 'required',
            'serial_no' => 'required',
            'brand_id' => 'required',
            'asset_no' => 'required',
            'location' => 'required',
            'req_date' => 'required',
            'arrived_date' => 'required'
        ]);
        $asset = MstAsset::where('id', '=', $id)->first();
        $asset->model_type_id = $request->modeltype_id;
        $asset->brand_id = $request->brand_id;
        $asset->category_id = $request->category_id;
        $asset->serial_no = $request->serial_no;
        $asset->asset_no = $request->asset_no;
        $asset->allocation = $request->allocation;
        $asset->request_dt = date('Y-m-d', strtotime($request->req_date));
        $asset->goods_arrived_dt = date('Y-m-d', strtotime($request->arrived_date));
        $asset->note = $request->note;
        $asset->specification = $request->specification;
        $asset->city_id = $request->location;
        $asset->allocation = $request->allocation;
        $asset->updated_by = Auth::user()->uuid;
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $hashName = $files->hashName();
            $folderName = public_path() . '/img/ga/asset';
            $fileName = $hashName;
            $asset->file_image_nm = $fileName;
            Storage::disk('asset_image')->put('asset', $files);
        };
        if ($asset->update()) {
            return redirect()->route('ga.asset.index')->with('alert.success', 'Asset Has Been Updated');
        }
        return redirect()->back()->with('alert.failed', 'Failed To Update Asset ');
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

    public function dataTables()
    {
        $query = MstAsset::query();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('brand', function ($query) {
                return '<a href="' . route('ga.asset.show', ['id' => $query->id]) . '">' . $query->brand . '</a>';
            })
            ->addColumn('action', function ($query) {
                return view('ga.asset.layouts._action', [
                    'model' => $query,
                    'url_edit' => route(
                        'ga.asset.edit',
                        $query
                    )
                ]);
            })
            ->rawColumns(['action', 'brand'],)
            ->make(true);
    }
    public function getTypeModel(Request $request)
    {
        $brand_id = $request->brand_id;
        $modelType = ModelTypeAsset::query()->select(['model_type_id as id', 'model_type_nm as name'])
            ->where('brand_id', '=', $brand_id)
            ->whereNull('deleted_at')->get();
        return response()->json($modelType);
    }

    public function checkin($id)
    {
        $query = TransaksiAsset::where('asset_id', '=', $id);
        return view('ga.asset.checkin', ['model' => $query->first()]);
    }

    public function checkinStore(Request $request)
    {
        // dd($request->input());
        $this->validate($request, [
            'file'       => 'required',
            'status_id' => 'required',
            'checkin_date' => 'required'
        ]);
        $asset_id = $request->input('asset_id');
        $checkin_at = $request->input('checkin_date');
        $status_id = $request->input('status_id');
        $note = $request->input('note');
        $checkin_from = $request->input('checkout_to');
        $checkout_at = $request->input('checkout_at');
        $request->hasFile('file');
        $files = $request->file('file');
        $hashName = $files->hashName();
        $folderName = 'ga/asset';
        $fileName = $hashName;
        $files->store($folderName);

        $checkin = new CheckInAsset();
        $checkin->asset_id = $asset_id;
        $checkin->checkin_from = $checkin_from;
        $checkin->checkout_at = date('Y-m-d', strToTime($checkout_at));
        $checkin->checkin_at = date('Y-m-d', strToTime($checkin_at));
        $checkin->note = $note;
        $checkin->file_nm = $fileName;
        $checkin->created_by = Auth::user()->uuid;
        $asset = MstAsset::findOrFail($asset_id);
        if ($checkin->save()) {
            $transaksi_asset = TransaksiAsset::query()->where('asset_id', '=', $asset_id);
            $transaksi_asset->update([
                'status_id' => $status_id,
                'checkin_id' => $checkin->id,
                'checkout_id' => null
            ]);
            return redirect()->route('ga.asset.index')->with('alert.success', 'Check-In ' . $asset->brand_nm . ' Has Been Saved');
        }
        return redirect()->back()->with('alert.failed', 'Failed Create Asset ' . $asset->brand_nm);
    }

    public function checkOut($id)
    {
        // dd($id);
        // $data = MstAsset::findOrFail($id);
        $query = TransaksiAsset::where(
            'asset_id',
            '=',
            $id
        );
        return view('ga.asset.checkout', ['model' => $query->first()]);
    }
    public function checkOutStore(Request $request)
    {
        $this->validate($request, [
            'file'       => 'required',
            'department_code' => 'required',
            'checkout_to' => 'required',
            'checkout_date' => 'required',
            'location' => 'required'
        ]);

        $asset_id = $request->input('asset_id');
        $checkoutTo = $request->input('checkout_to');
        $department = $request->input('department_code');
        $checkout_td = $request->input('checkout_date');
        $note = $request->input('note');
        $location = $request->input('location');


        $request->hasFile('file');
        $files = $request->file('file');
        $hashName = $files->hashName();
        $folderName = 'ga/asset';
        $fileName = $hashName;

        $files->store($folderName);

        $checkout = new CheckoutAsset();
        $checkout->asset_id = $asset_id;
        $checkout->checkout_to = $checkoutTo;
        $checkout->department_code = $department;
        $checkout->checkout_at = date('Y-m-d', strtotime($checkout_td));
        $checkout->note = $note;
        $checkout->location = $location;
        $checkout->created_by = Auth::user()->uuid;
        $checkout->file_nm = $fileName;
        $asset = MstAsset::findOrFail($asset_id);
        if ($checkout->save()) {
            $transaksi_asset = TransaksiAsset::query()->where('asset_id', '=', $asset_id);
            $transaksi_asset->update([
                'status_id' => '6',
                'checkout_id' => $checkout->checkout_id
            ]);
            return redirect()->route('ga.asset.index')->with('alert.success', 'Checkout ' . $asset->brand_nm . ' Has Been Saved');
        }
        return redirect()->back()->with('alert.failed', 'Failed Create Asset ' . $asset->brand_nm);
    }
    public function getUserDepartment(Request $request)
    {
        $empl = [];
        $employee = EmployeeAsset::query();
        $employee->leftjoin('asset_emp_department as aed', 'aed.employee_uuid', '=', 'uuid');
        $employee->where('aed.department_code', '=', $request->department_code);
        $employee->Select(['uuid', 'name']);


        return response()->json($employee->get());
    }
    public function getDepartmentByUser(Request $request)
    {
        // dd($request->input());
        $data = [];
        $query = DepartmentAsset::query();
        $query->leftjoin('asset_emp_department as aed', 'aed.department_code', '=', 'code');
        $query->where('aed.employee_uuid', '=', $request->uuid);
        $query->Select('code', 'name');

        return response()->json($query->get());
    }

    public function createDepartment()
    {
        $departments = DepartmentAsset::all();
        return view('ga.asset.formDepartment', ['departments' => $departments]);
    }
    public function storeDepartment(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        $code = Str::Random(3) . date('mds');
        $department = new DepartmentAsset();
        $department->code = $code;
        $department->name = $request->name;
        $department->created_by = Auth::user()->uuid;
        $department->save();
    }

    public function createUserAsset()
    {
        return view('ga.asset.formUser');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'department_code' => ['required']
        ]);
    }
    public function storeUserAsset(Request $request)
    {
        // $asset_id = $request->asset_id;
        $this->validator($request->all())->validate();
        $employee = new EmployeeAsset();
        $employee->name = $request->name;
        $employee->save();

        if ($employee->save()) {
            $empldep = new EmployeeDepartment();
            $empldep->employee_uuid = $employee->uuid;
            $empldep->department_code = $request->department_code;
            $empldep->save();
        }
        // return redirect()->route('ga.asset.checkout', ['id' => $asset_id])->with('alert.success', 'Success Registered');
    }
}
