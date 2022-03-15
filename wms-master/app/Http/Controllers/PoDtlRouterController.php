<?php

namespace App\Http\Controllers;

use App\Models\PoDtl;
use App\Models\PoDtlUploadHistory;
use App\Models\PurchaseType;
use App\Models\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PoDtlRouterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $po_dtl_id
     * @return \Illuminate\Http\Response
     */
    public function index($po_dtl_id)
    {
        $poDtl = PoDtl::findOrFail($po_dtl_id);
        return view('po.dtl.router.index', ['poDtl' => $poDtl]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $po_dtl_id
     * @return \Illuminate\Http\Response
     */
    public function create($po_dtl_id)
    {
        $model = new Router();
        return view('po.dtl.router.form', ['po_dtl_id' => $po_dtl_id, 'model' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Router($request->all());
        $model->status_id = 1;
        if (!$request->condition) {
            $model->condition = 'GOOD';
        }
        $model->created_by = Auth::user()->id;
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
        $model = Router::findOrFail($id);
        return view('po.dtl.router.form', ['model' => $model]);
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
        $model = Router::findOrFail($id);
        $model->esn = $request->esn;
        $model->ssid = $request->ssid;
        $model->password_router = $request->password_router;
        $model->guest_ssid = $request->guest_ssid;
        $model->password_guest = $request->password_guest;
        $model->password_admin = $request->password_admin;
        $model->imei = $request->imei;
        $model->device_model = $request->device_model;
        $model->device_type = $request->device_type;
        $model->color = $request->color;
        $model->condition = $request->condition;
        $model->updated_by = Auth::user()->id;
        $model->save();
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
        $query = Router::query()->where('po_dtl_id', '=', $request->po_dtl_id);
        return DataTables::eloquent($query)
            ->addColumn('action', function($model) {
                return view('po.dtl.router.action', ['model' => $model]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
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
        $file->move('data_excel/router',$nama_file);
        $inputFileName = public_path('/data_excel/router/'.$nama_file);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($inputFileName);
        $worksheet = $spreadSheet->getActiveSheet();
        $lastRow = $worksheet->getHighestRow();

        $data = [];
        $created_at = date('Y-m-d H:i:s');
        $conditions = ['GOOD', 'BAD'];
        $purchaseTypes = PurchaseType::all();
        $purchaseTypeIds = [];
        foreach ($purchaseTypes as $purchaseType) {
            $types[$purchaseType->name] = $purchaseType->id;
        }
        $purchaseTypeNames = array_keys($purchaseTypeIds);
        for ($row = 2; $row <= $lastRow; $row++) {
            if (!empty($worksheet->getCell('A'.$row)->getValue())) {
                $router = [];
                $router['po_dtl_id'] = $request->po_dtl_id;
                $router['esn'] = $worksheet->getCell('A'.$row)->getValue();
                $router['ssid'] = $worksheet->getCell('C'.$row)->getValue();
                $router['password_router'] = $worksheet->getCell('D'.$row)->getValue();
                $router['guest_ssid'] = $worksheet->getCell('E'.$row)->getValue();
                $router['password_guest'] = $worksheet->getCell('F'.$row)->getValue();
                $router['password_admin'] = $worksheet->getCell('G'.$row)->getValue();
                $router['imei'] = $worksheet->getCell('H'.$row)->getValue();
                $router['device_model'] = $worksheet->getCell('I'.$row)->getValue();
                $router['device_type'] = $worksheet->getCell('J'.$row)->getValue();
                $router['status_id'] = 1;

                $router['purchase_type_id'] = null;
                $purchase_type_name = $worksheet->getCell('K'.$row)->getValue();
                if (!empty($purchase_type) && in_array($purchase_type_name, $purchaseTypeNames)) {
                    $router['purchase_type_id'] = $purchaseTypeIds[$purchase_type_name];
                }

                $router['condition'] = 'GOOD';
                $condition = $worksheet->getCell('M'.$row)->getValue();
                if (!empty($condition) && in_array($condition, $conditions)) {
                    $router['condition'] = $condition;
                }

                $router['created_at'] = $created_at;
                $router['created_by'] = Auth::user()->id;
                $data[] = $router;
                if (count($data) % 100 === 1 || $row === $lastRow) {
                    DB::table('routers')->insert($data);
                    $data = [];
                }
            }
        }

        $history = new PoDtlUploadHistory();
        $history->po_dtl_id = $request->po_dtl_id;
        $history->filename = 'data_excel/router/' . $nama_file;
        $history->created_by = Auth::user()->id;
        $history->save();
    }
}
