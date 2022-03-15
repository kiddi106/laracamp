<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Order;
use FFI;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model['requested'] = Order::where('status_id',1)->count();
        $model['received'] = Order::where('status_id',3)->count();
        $model['ready'] = Order::where('status_id',4)->count();
        $model['waiting'] = Order::where('status_id',5)->count();
        $model['picked'] = Order::where('status_id',6)->count();
        $model['cancel'] = Order::where('status_id',7)->count();
        return view('order.index',$model);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Order::find($id);
        return view('order.show',['model'=>$model]);
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

    public function datatable(Request $request)
    {
        $query = DB::table('orders')
                    ->join('mst_status', 'orders.status_id', '=', 'mst_status.id')
                    ->join('purchase_types', 'orders.purchase_type_id', '=', 'purchase_types.id')
                    ->join('order_receiver', 'orders.receiver_id', '=', 'order_receiver.id')
                    ->join('order_delivery', 'orders.delivery_id', '=', 'order_delivery.id')
                    ->join('users AS created_user', 'orders.created_by', '=', 'created_user.id')
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->select([
                            'orders.*',
                            'order_items.sku AS sku',
                            'mst_status.name AS status_name', 'mst_status.bgcolor',
                            'purchase_types.name AS purchase_type_name',
                            'order_receiver.name AS customer_name', 'order_receiver.destination',
                            'order_delivery.type AS delivery_name', 'order_delivery.do_date',
                            'created_user.name AS created_user_name'
                        ]);

        $fields = ['order_number', 'document_number', 'purchase_type_id', 'status_id', 'awb'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->$field) {
                $query->where('orders.' . $field, '=', $request->$field);
            }
        }

        return DataTables::query($query)
            ->addIndexColumn()
            ->editColumn('do_date', function ($model) {
                return date('d/M/Y H:i:s', strtotime($model->do_date));
            })
            ->editColumn('created_at', function ($model) {
                return date('d/M/Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('status_name', function ($model) {
                return '<span class="badge ' . $model->bgcolor . '">' . $model->status_name . '</span>';
            })
            ->addColumn('action', function ($model) {
                return '<a href="'.route('order.show', ["id" => $model->id]).'" class="btn btn-xs btn-default" title="Show"><i class="fas fa-eye"></i></a>';
            })
            ->rawColumns(['status_name','action'])
            ->make(true);
    }
    public function tanggal($date)
    {
        $exp = explode('-', $date);
        $date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
        return $date;
    }

    public function report($date)
    {
        ob_end_clean(); // this
        ob_start(); // and this
        set_time_limit(0);

        $date = explode(' to ', urldecode($date));
        $startDate = $this->tanggal($date[0]);
        $endDate = $this->tanggal($date[1]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ORDER NUMBER');
        $sheet->setCellValue('C1', 'STATUS');
        $sheet->setCellValue('D1', 'PURCHASE TYPE');
        $sheet->setCellValue('E1', 'CUSTOMER NUMBER');
        $sheet->setCellValue('F1', 'DOCUMENT NUMBER');
        $sheet->setCellValue('G1', 'NOTES');
        $sheet->setCellValue('H1', 'AWB');
        $sheet->setCellValue('I1', 'REQUEST DATE');
        $sheet->setCellValue('J1', 'RECEIVE DATE');
        $sheet->setCellValue('K1', 'CUSTOMER NAME');
        $sheet->setCellValue('L1', 'PHONE');
        $sheet->setCellValue('M1', 'POSTAL CODE');
        $sheet->setCellValue('N1', 'DESTINATION');
        $sheet->setCellValue('O1', 'DELIVERY TYPE');
        $sheet->setCellValue('P1', 'DO DATE');
        $sheet->setCellValue('Q1', 'PICK UP NAME');
        $sheet->setCellValue('R1', 'DRIVER');
        $sheet->setCellValue('S1', 'VEHICLE');
        $sheet->setCellValue('T1', 'POLICE NO');
        $sheet->setCellValue('U1', 'PICKED DATE');
        $sheet->setCellValue('V1', 'SKU REQUEST');
        $sheet->setCellValue('W1', 'QTY REQUEST');
        $sheet->setCellValue('X1', 'IMEI');
        $sheet->setCellValue('Y1', 'MSISDN');
        $sheet->setCellValue('Z1', 'STOCK NAME');

        $query = DB::table('orders as o')
                    ->leftJoin('mst_status as ms','ms.id','=','o.status_id')
                    ->leftJoin('purchase_types as pt','pt.id','=','o.purchase_type_id')
                    ->leftJoin('order_receiver as or2','or2.id','=','o.receiver_id')
                    ->leftJoin('order_delivery as od','od.id','=','o.delivery_id')
                    ->leftJoin('order_pick_up as opu','opu.id','=','o.pick_up_id')
                    ->leftJoin('order_items as oi','oi.order_id','=','o.id')
                    ->leftJoin('order_item_orbits as oio','oio.order_item_id','=','oi.id')
                    ->leftJoin('orbit_stocks as os','os.id','=','oio.orbit_stock_id')
                    ->leftJoin('routers as r','os.router_id','=','r.id')
                    ->leftJoin('simcards as s','os.simcard_id','=','s.id')
                    ->leftJoin('po_dtl as pd','r.po_dtl_id','=','pd.id')
                    ->leftJoin('material as m','m.id','=','pd.material_id')
                    ->select([
                        'o.order_number AS ORDER_NUMBER', 
                        'ms.name AS STATUS', 
                        'pt.name AS  PURCHASE_TYPE', 
                        'o.customer_number AS CUSTOMER_NUMBER', 
                        'o.document_number AS DOCUMENT_NUMBER',
                        'o.notes AS NOTES', 
                        'o.awb AS AWB', 
                        'o.created_at AS REQUEST_DATE', 
                        'o.received_at AS RECEIVE_DATE',
                        'or2.name AS CUSTOMER_NAME', 
                        'or2.phone AS PHONE', 
                        'or2.postal_code AS POSTAL_CODE', 
                        'or2.destination AS DESTINATION',
                        'od.type AS DELIVERY_TYPE', 
                        'od.do_date AS DO_DATE', 
                        'opu.name AS PICKUP_NAME', 
                        'opu.driver AS DRIVER', 
                        'opu.vehicle AS VEHICLE', 
                        'opu.police_no AS POLICE_NO', 
                        'opu.picked_at AS PICKED_DATE',
                        'oi.sku AS SKU_REQUEST', 
                        'oi.qty AS QTY_REQUEST', 
                        'r.imei AS IMEI' , 
                        's.msisdn AS MSISDN', 
                        'm.name AS STOCK_NAME',
                        'o.created_at'
                    ])
                    ->whereBetween('o.created_at', [$startDate, $endDate])
                    ->get();
        $idx = 2;

        foreach ($query as $key => $value) {
            $sheet->setCellValue('A'.$idx, $key+1);
            $sheet->setCellValueExplicit('B'.$idx, $value->ORDER_NUMBER, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C'.$idx, $value->STATUS);
            $sheet->setCellValue('D'.$idx, $value->PURCHASE_TYPE);
            $sheet->setCellValue('E'.$idx, $value->CUSTOMER_NUMBER);
            $sheet->setCellValueExplicit('F'.$idx, $value->DOCUMENT_NUMBER, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            // $sheet->setCellValue('F'.$idx, $value->DOCUMENT_NUMBER);
            $sheet->setCellValue('G'.$idx, $value->NOTES);
            $sheet->setCellValue('H'.$idx, $value->AWB);
            $sheet->setCellValue('I'.$idx, $value->REQUEST_DATE);
            $sheet->setCellValue('J'.$idx, $value->RECEIVE_DATE);
            $sheet->setCellValue('K'.$idx, $value->CUSTOMER_NAME);
            $sheet->setCellValue('L'.$idx, $value->PHONE);
            $sheet->setCellValue('M'.$idx, $value->POSTAL_CODE);
            $sheet->setCellValue('N'.$idx, $value->DESTINATION);
            $sheet->setCellValue('O'.$idx, $value->DELIVERY_TYPE);
            $sheet->setCellValue('P'.$idx, $value->DO_DATE);
            $sheet->setCellValue('Q'.$idx, $value->PICKUP_NAME);
            $sheet->setCellValue('R'.$idx, $value->DRIVER);
            $sheet->setCellValue('S'.$idx, $value->VEHICLE);
            $sheet->setCellValue('T'.$idx, $value->POLICE_NO);
            $sheet->setCellValue('U'.$idx, $value->PICKED_DATE);
            $sheet->setCellValue('V'.$idx, $value->SKU_REQUEST);
            $sheet->setCellValue('W'.$idx, $value->QTY_REQUEST);
            $sheet->setCellValueExplicit('X'.$idx, $value->IMEI, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            // $sheet->setCellValue('X'.$idx, $value->IMEI);
            $sheet->setCellValueExplicit('Y'.$idx, $value->MSISDN, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            // $sheet->setCellValue('Y'.$idx, $value->MSISDN);
            $sheet->setCellValue('Z'.$idx, $value->STOCK_NAME);
            $idx++;
        }
        // $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        $nama = 'Report Order ('.$date[0].' to '.$date[1].')';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    
}
