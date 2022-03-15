<?php

namespace App\Http\Controllers;

use App\Models\OrbitStock;
use App\Models\PurchaseType;
use App\Models\ReworkUploadHistory;
use App\Models\Router;
use App\Models\Simcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rework.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'router_id' => 'required',
            'simcard_id' => 'required',
            'purchase_type_id' => 'required'
        ]);

        $model = new OrbitStock($request->all());
        $model->status_id = 2;
        $model->created_by = Auth::user()->id;
        $model->save();

        $router = Router::findOrFail($request->router_id);
        $router->status_id = 2;
        $router->purchase_type_id = $request->purchase_type_id;
        $router->location = $request->location;
        $router->save();

        $simcard = Simcard::findOrFail($request->simcard_id);
        $simcard->status_id = 2;
        $simcard->purchase_type_id = $request->purchase_type_id;
        $simcard->location = $request->location;
        $simcard->save();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

    /**
     * Datatables Ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        $query = DB::table('orbit_stocks')
                    ->join('routers', 'orbit_stocks.router_id', '=', 'routers.id')
                    ->join('po_dtl', 'routers.po_dtl_id', '=', 'po_dtl.id')
                    ->join('material', 'po_dtl.material_id', '=', 'material.id')
                    ->join('simcards', 'orbit_stocks.simcard_id', '=', 'simcards.id')
                    ->join('purchase_types', 'orbit_stocks.purchase_type_id', '=', 'purchase_types.id')
                    ->where('orbit_stocks.status_id', '=', 2)
                    ->select([
                        'orbit_stocks.*',
                        'routers.esn', 'routers.ssid', 'routers.password_router', 'routers.guest_ssid', 'routers.password_guest', 'routers.password_admin', 'routers.imei', 'routers.device_model', 'routers.device_type', 'routers.color',
                        'simcards.serial_no', 'simcards.msisdn', 'simcards.item_code',
                        'purchase_types.name AS purchase_type_name',
                        'material.name AS material_name'
                    ]);
        return DataTables::query($query)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Datatables Ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getOrbit(Request $request)
    {
        if ($request->imei || $request->ssid) {
            $query = DB::table('orbit_stocks')
                        ->join('routers', 'orbit_stocks.router_id', '=', 'routers.id')
                        ->join('po_dtl', 'routers.po_dtl_id', '=', 'po_dtl.id')
                        ->join('material', 'po_dtl.material_id', '=', 'material.id')
                        ->join('simcards', 'orbit_stocks.simcard_id', '=', 'simcards.id')
                        ->join('purchase_types', 'orbit_stocks.purchase_type_id', '=', 'purchase_types.id')
                        ->where('orbit_stocks.status_id', '=', 2)
                        ->select([
                            'orbit_stocks.*',
                            'routers.esn', 'routers.ssid', 'routers.password_router', 'routers.guest_ssid', 'routers.password_guest', 'routers.password_admin', 'routers.imei', 'routers.device_model', 'routers.device_type', 'routers.color',
                            'simcards.serial_no', 'simcards.msisdn', 'simcards.item_code',
                            'purchase_types.name AS purchase_type_name',
                            'material.name AS material_name'
                        ]);

            if ($request->imei) {
                $query->where('routers.imei', '=', $request->imei);
            }

            if ($request->msisdn) {
                $query->where('simcards.msisdn', '=', $request->msisdn);
            }

            $stock = $query->first();
            if ($stock) {
                return response()->json($stock);
            }
        }
        return response()->status(404);
    }

    /**
     * Upload Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('data_excel/rework',$nama_file);
        $inputFileName = public_path('/data_excel/rework/'.$nama_file);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($inputFileName);
        $worksheet = $spreadSheet->getActiveSheet();
        $lastRow = $worksheet->getHighestRow();

        // create and insert datatable
        $tblTemp = 'rework_temp_' . date('YmdHis');
        DB::statement("CREATE TABLE $tblTemp (msisdn varchar(255) NOT NULL, imei varchar(255) NOT NULL, `purchase_type_id` int(10) unsigned NOT NULL);");
        $dataTemp = [];
        $purchaseTypes = PurchaseType::all();
        $purchaseTypeIds = [];
        foreach ($purchaseTypes as $purchaseType) {
            $types[$purchaseType->name] = $purchaseType->id;
        }
        $created_at = date('Y-m-d H:i:s');
        $purchaseTypeNames = array_keys($purchaseTypeIds);
        for ($row = 2; $row <= $lastRow; $row++) {
            if (!empty($worksheet->getCell('B'.$row)->getValue()) && !empty($worksheet->getCell('H'.$row)->getValue())) {
                $router = [];
                $router['msisdn'] = $worksheet->getCell('B'.$row)->getValue();
                $router['imei'] = $worksheet->getCell('H'.$row)->getValue();
                $router['purchase_type_id'] = 1;
                $purchase_type_name = $worksheet->getCell('K'.$row)->getValue();
                if (!empty($purchase_type) && in_array($purchase_type_name, $purchaseTypeNames)) {
                    $router['purchase_type_id'] = $purchaseTypeIds[$purchase_type_name];
                }
                $dataTemp[] = $router;
                if (count($dataTemp) % 100 === 1 || $row === $lastRow) {
                    DB::table($tblTemp)->insert($dataTemp);
                    $dataTemp = [];
                }
            }
        }
        $imeiNotFounds = DB::table($tblTemp . ' as a')
            ->leftJoin('routers as b', 'a.imei', '=', 'b.imei')
            ->whereNull('b.imei')
            ->select(['a.imei'])
            ->get();
        $msisdnNotFounds = DB::table($tblTemp . ' as a')
            ->leftJoin('simcards as b', 'a.msisdn', '=', 'b.msisdn')
            ->whereNull('b.msisdn')
            ->select(['a.msisdn'])
            ->get();

        if (count($imeiNotFounds) > 0 && count($msisdnNotFounds) > 0) {
            DB::statement("DROP TABLE $tblTemp;");
            return view('rework.upload_notfound', ['imeiNotFounds' => $imeiNotFounds, 'msisdnNotFounds' => $msisdnNotFounds]);
        }

        $history = new ReworkUploadHistory();
        $history->filename = 'data_excel/rework/' . $nama_file;
        $history->created_by = Auth::user()->id;
        $history->save();

        $dataUpload = DB::table($tblTemp . ' as a')
                ->leftJoin('routers as b', 'a.imei', '=', 'b.imei')
                ->leftJoin('simcards as c', 'a.msisdn', '=', 'c.msisdn')
                ->select(['b.id as router_id', 'c.id as simcard_id', 'a.purchase_type_id'])
                ->get();
        $data = [];
        foreach ($dataUpload as $stock) {
            $data[] = [
                'router_id' => $stock->router_id,
                'simcard_id' => $stock->simcard_id,
                'status_id' => 2,
                'purchase_type_id' => $stock->purchase_type_id,
                'upload_history_id' => $history->id,
                'created_at' => $created_at,
                'created_by' => Auth::user()->id
            ];
            if (count($data) % 100 === 1 || $row === $lastRow) {
                DB::table('orbit_stocks')->insert($data);
                $data = [];
            }
        }
        DB::statement("DROP TABLE $tblTemp;");

        return redirect()->route('rework.index');
    }
}
