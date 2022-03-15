<?php

namespace App\Http\Controllers;

use App\Models\PoDtl;
use App\Models\PoDtlUploadHistory;
use App\Models\PurchaseType;
use App\Models\Simcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PoDtlSimcardController extends Controller
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
        return view('po.dtl.simcard.index', ['poDtl' => $poDtl]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $po_dtl_id
     * @return \Illuminate\Http\Response
     */
    public function create($po_dtl_id)
    {
        $model = new Simcard();
        return view('po.dtl.simcard.form', ['po_dtl_id' => $po_dtl_id, 'model' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Simcard($request->all());
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
        $model = Simcard::findOrFail($id);
        return view('po.dtl.simcard.form', ['model' => $model]);
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
        $model = Simcard::findOrFail($id);
        $model->serial_no = $request->serial_no;
        $model->msisdn = $request->msisdn;
        $model->item_code = $request->item_code;
        $model->exp_at = $request->exp_at;
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
        $query = Simcard::query()->where('po_dtl_id', '=', $request->po_dtl_id);
        return DataTables::eloquent($query)
            ->addColumn('action', function($model) {
                return view('po.dtl.simcard.action', ['model' => $model]);
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
        $file->move('data_excel/simcard',$nama_file);
        $inputFileName = public_path('/data_excel/simcard/'.$nama_file);

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
                $simcard = [];
                $simcard['po_dtl_id'] = $request->po_dtl_id;
                $simcard['serial_no'] = $worksheet->getCell('A'.$row)->getValue();
                $simcard['msisdn'] = $worksheet->getCell('B'.$row)->getValue();
                $simcard['item_code'] = $worksheet->getCell('C'.$row)->getValue();

                $date = $worksheet->getCell('D'.$row)->getValue();
                $simcard['exp_at'] = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($date));

                $simcard['status_id'] = 1;

                $simcard['purchase_type_id'] = null;
                $purchase_type_name = $worksheet->getCell('E'.$row)->getValue();
                if (!empty($purchase_type) && in_array($purchase_type_name, $purchaseTypeNames)) {
                    $simcard['purchase_type_id'] = $purchaseTypeIds[$purchase_type_name];
                }

                $simcard['condition'] = 'GOOD';
                $condition = $worksheet->getCell('F'.$row)->getValue();
                if (!empty($condition) && in_array($condition, $conditions)) {
                    $simcard['condition'] = $condition;
                }

                $simcard['created_at'] = $created_at;
                $simcard['created_by'] = Auth::user()->id;
                $data[] = $simcard;
                if (count($data) % 100 === 1 || $row === $lastRow) {
                    DB::table('simcards')->insert($data);
                    $data = [];
                }
            }
        }

        $history = new PoDtlUploadHistory();
        $history->po_dtl_id = $request->po_dtl_id;
        $history->filename = 'data_excel/simcard/' . $nama_file;
        $history->created_by = Auth::user()->id;
        $history->save();
    }
}
