<?php

namespace App\Http\Controllers;

use App\Models\ApiSendLog;
use App\Models\OrbitStock;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderPickUp;
use App\Models\OrderReceiver;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class PickpackController extends Controller
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
        return view('pickpack.index',$model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Order();
        return view('pickpack.form', ['model' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate(['document_number'=> 'required', 
        'purchase_type_id'=>'required',
        'awb'=>'required',
        'notes'=>'required',
        'customer_number'=>'required',
        'receiver_name'=>'required',
        'receiver_phone'=>'required',
        'receiver_postal_code'=>'required']);
        
        // $order=new Order();
        // $order->order_number='15';
        // $order->status_id='1';
        // $order->customer_number= $request->customer_number;
        // $order->purchase_type_id = $request->purchase_type_id;
        // $order->document_number = $request->document_number;
        // $order->awb = $request->awb;
        // $order->notes = $request->notes;
        // $order->created_by = Auth::user()->id;
        // $order->save();
        // if ($order->save()) {
        //     $receive= new OrderReceiver();
        //     $receive->order_id= $order->id;
        //     $receive->name = $request->receiver['name'];
        //     $receive->phone=$request->receiver['phone'];
        //     $receive->postal_code=$request->receiver['postal_code'];
        //     $receive->destination=$request->receiver['destination'];
        //     $receive->created_by = Auth::user()->id;
        //     $receive->save();

        //     $delivery = new OrderDelivery();
        //     $delivery->order_id=$order->id;
        //     $delivery->type=$request->delivery['type'];
        //     $delivery->do_date=$request->delivery['do_date'];
        //     $delivery->created_by = Auth::user()->id;
            
        //       $delivery->save();

        //     return redirect()->route('pickpack.index')->with('alert.success', 'Data has Been Saved');
        // }




    
        
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
        $model = Order::findOrFail($id);
        return view('pickpack.form', ['model' => $model]);
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
        $order = Order::findOrFail($id);
        $order->purchase_type_id = $request->purchase_type_id;
        $order->document_number = $request->document_number;
        $order->awb = $request->awb;
        $order->notes = $request->notes;
        $order->customer_number = $request->customer_number;
        $order->save();

        if ($order->delivery_id != $request->delivery['id']) {
            return redirect()->back()->with('alert.error', 'Delivery ID');
        }
        $delivery = OrderDelivery::findOrFail($order->delivery_id);
        $delivery->type = $request->delivery['type'];
        $delivery->do_date = str_replace('T', ' ', $request->delivery['do_date']);
        $delivery->save();

        $pickUp = new OrderPickUp();
        if (in_array($order->status_id, [3, 4]) && !$order->pick_up_id) {
            $pick_up_number = 'PU' . date('Ymd');
            $lastPickUp = OrderPickUp::query()->where('pick_up_number', 'LIKE', $pick_up_number . '%')->orderBy('id', 'desc')->first();
            $lastNumber = '';
            if ($lastPickUp) {
                $lastNumber = str_replace($pick_up_number, '', $lastPickUp->pick_up_number);
            } else {
                $lastNumber = sprintf('%07d', 0);
            }
            $lastNumber = ((int) $lastNumber) + 1;
            $pick_up_number .= sprintf('%07d', $lastNumber);

            $pickUp->pick_up_number = $pick_up_number;
            $pickUp->order_id = $order->id;
            $pickUp->name = $delivery->type;
            $pickUp->driver = '';
            $pickUp->vehicle = '';
            $pickUp->police_no = '';
            $pickUp->created_by = Auth::user()->id;
            $pickUp->save();

            $order->pick_up_id = $pickUp->id;
            $order->save();
        } elseif ($order->pick_up_id) {
            $pickUp = OrderPickUp::findOrFail($order->pick_up_id);
            if ($request->pick_up && is_array($request->pick_up)) {
                $pickUp->name = $request->pick_up['name'];
                $pickUp->driver = $request->pick_up['driver'] ?: '';
                $pickUp->vehicle = $request->pick_up['vehicle'] ?: '';
                $pickUp->police_no = $request->pick_up['police_no'] ?: '';
                $pickUp->picked_at = $request->pick_up['picked_at'] ? str_replace('T', ' ', $request->pick_up['picked_at']) : null;
                $pickUp->save();
            }
        }

        if ($order->receiver_id != $request->receiver['id']) {
            return redirect()->back()->with('alert.error', 'Receiver ID');
        }
        $receiver = OrderReceiver::findOrFail($order->receiver_id);
        $receiver->name = $request->receiver['name'];
        $receiver->phone = $request->receiver['phone'];
        $receiver->postal_code = $request->receiver['postal_code'];
        $receiver->save();

        $createdBy = User::where('id', '=', $order->created_by)->first();

        $dateNow = date('Y-m-d H:i:s');
        if ($order->status_id != $request->status_id) {
            $updateStatus = false;
            if ($order->status_id == 1 && $request->status_id == 3) {
                $order->received_by = Auth::user()->id;
                $order->received_at = $dateNow;
                $updateStatus = true;
            } elseif ($order->status_id == 3 && $request->status_id == 4) { // Received -> Ready to Pick Up
                if ($createdBy->hasRole('client-orbit')) {
                    // TODO hit api receive
                    $url = env('ORBIT_URL') . 'order/v1/shipments/mitracomm/webhook/handling';
                    $apiRequest = [
                        'type' => 'delivery',
                        'channel' => 'MBSD',
                        'data' => [
                                'document_number' => $order->document_number,
                                'order_number' => $order->order_number,
                                'notes' => $order->notes,
                                'status' => 'packed',
                                'estimation_send_date' => $order->estimation_send_date,
                                'delivery' => [
                                    'type' => $delivery->type,
                                    'do_date' => $delivery->do_date
                                ]
                            ],
                        'receiver' => [
                            'name' => $receiver->name,
                            'phone' => $receiver->phone,
                            'postal_code' => $receiver->postal_code,
                            'destination' => $receiver->destination
                        ]
                    ];
                    $apiResponse = Http::post($url, $apiRequest);
                    if ($apiResponse->successful()) {
                        $updateStatus = true;
                    }
                    $apiSendLog = new ApiSendLog();
                    $apiSendLog->header = json_encode($apiResponse->headers());
                    $apiSendLog->ip = '::1';
                    $apiSendLog->url = $url;
                    $apiSendLog->method = 'POST';
                    $apiSendLog->status_code = $apiResponse->status();
                    $apiSendLog->request = json_encode($apiRequest);
                    $apiSendLog->response = json_encode($apiResponse->json());
                    $apiSendLog->data_internal = json_encode([
                        'order_id' => $order->id
                    ]);
                    $apiSendLog->created_by = Auth::user()->id;
                    $apiSendLog->save();
                }
            }
            // elseif ($order->status_id == 4 && $request->status_id == 5) {
            //     $updateStatus = true;
            // }
            elseif ($order->status_id == 4 && $request->status_id == 6) { // Ready to Pick Up -> Picked Up
                if ($pickUp->picked_at) {
                    if ($createdBy->hasRole('client-orbit')) {
                        $items = DB::table('orders AS o')
                            ->join('order_items AS oi', 'o.id', '=', 'oi.order_id')
                            ->join('order_item_orbits AS oio', 'oi.id', '=', 'oio.order_item_id')
                            ->join('orbit_stocks AS os', 'oio.orbit_stock_id', '=', 'os.id')
                            ->join('routers AS r', 'os.router_id', '=', 'r.id')
                            ->join('po_dtl AS pd', 'r.po_dtl_id', '=', 'pd.id')
                            ->join('material AS m', 'pd.material_id', '=', 'm.id')
                            ->join('simcards AS s', 'os.simcard_id', '=', 's.id')
                            ->where('o.id', '=', $order->id)
                            ->select(['o.order_number', 'oi.sku', 'r.imei', 's.msisdn', 'm.name', 'o.id AS order_id', 'oi.id AS order_item_id', 'os.id AS orbit_stock_id', 'r.id AS router_id', 's.id AS simcard_id'])
                            ->get();
                        if (count($items) == 0) {
                            return redirect()->back()->with('alert.failed', 'Order Items is Empty');
                        }
                        // TODO hit api receive
                        $url = env('ORBIT_URL') . 'order/v1/shipments/mitracomm/webhook/handling';
                        $apiRequest = [
                            'type' => 'outbounds',
                            'channel' => 'MBSD',
                            'data' => [
                                'document_number' => $order->document_number,
                                'order_number' => $order->order_number,
                                'outbound_number' => $pickUp->pick_up_number,
                                'notes' => $order->notes,
                                'courier' => [
                                    'name' => $pickUp->name,
                                    'driver' => $pickUp->driver,
                                    'vehicle' => $pickUp->vehicle,
                                    'police_no' => $pickUp->police_no,
                                ]
                            ]
                        ];

                        $dataItems = [];
                        foreach ($items as $item) {
                            $dataItems[] = [
                                'sku' => $item->sku,
                                'name' => 'BUNDLING ROUTER ' . (str_replace("ZTE-", "", $item->name)) . '+ SIMCARD',
                                'qty' => 1,
                                'imei' => $item->imei,
                                'msisdn' => $item->msisdn
                            ];
                        }
                        $apiRequest['data']['items'] = $dataItems;

                        $apiResponse = Http::post($url, $apiRequest);
                        if ($apiResponse->successful()) {
                            $updateStatus = true;

                            foreach ($items as $item) {
                                $orbitStock = OrbitStock::findOrFail($item->orbit_stock_id);
                                $orbitStock->order_id = $order->id;
                                $orbitStock->order_item_id = $item->order_item_id;
                                $orbitStock->status_id = 6;
                                $orbitStock->save();
                            }
                        }
                        $apiSendLog = new ApiSendLog();
                        $apiSendLog->header = json_encode($apiResponse->headers());
                        $apiSendLog->ip = '::1';
                        $apiSendLog->url = $url;
                        $apiSendLog->method = 'POST';
                        $apiSendLog->status_code = $apiResponse->status();
                        $apiSendLog->request = json_encode($apiRequest);
                        $apiSendLog->response = json_encode($apiResponse->json());
                        $apiSendLog->data_internal = json_encode([
                            'order_id' => $order->id
                        ]);
                        $apiSendLog->created_by = Auth::user()->id;
                        $apiSendLog->save();


                    }
                }
            }
            if ($updateStatus) {
                $order->status_id = $request->status_id;
                $order->save();
            }
        }

        return redirect()->back()->with('alert.success', 'Data has been Updated');
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
            ->addColumn('checkbox', function ($model) {
                if ($model->status_id == 4) {
                    return '<input type="checkbox" name="order_ids[]" id="_order_id' . $model->id . '" class="checked-select" value="' . $model->id . '">';
                }
                return '';
            })
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
                return view('pickpack.action', ['model' => $model]);
            })
            ->rawColumns(['checkbox', 'status_name'])
            ->make(true);
    }

    /**
     * Print AWB.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function printAwb($id)
    {
        $model = Order::findOrFail($id);
        $pdf = PDF::loadView('pickpack.print_awb', ['model' => $model])->setPaper('a5', 'potrait');
        return $pdf->stream($model->awb.'.pdf');
    }

    public function pickUps(Request $request)
    {
        $this->validate($request, [
            'order_ids' => 'required|array',
            'driver' => 'required|string',
            'vehicle' => 'required|string',
            'police_no' => 'required|string',
            'picked_at' => 'required|date',
        ]);

        foreach ($request->order_ids as $order_id) {
            $order = Order::findOrFail($order_id);

            if ($order->status_id == 4) { // Ready to Pick Up -> Picked Up
                $delivery = OrderDelivery::findOrFail($order->delivery_id);
                if ($order->pick_up_id) {
                    $pickUp = OrderPickUp::findOrFail($order->pick_up_id);
                    $pickUp->driver = $request->driver;
                    $pickUp->vehicle = $request->vehicle;
                    $pickUp->police_no = $request->police_no;
                    $pickUp->picked_at = $request->picked_at;
                    $pickUp->updated_by = Auth::user()->id;
                } else {
                    $pickUp = new OrderPickUp();
                    $pick_up_number = 'PU' . date('Ymd');
                    $lastPickUp = OrderPickUp::query()->where('pick_up_number', 'LIKE', $pick_up_number . '%')->orderBy('id', 'desc')->first();
                    $lastNumber = '';
                    if ($lastPickUp) {
                        $lastNumber = str_replace($pick_up_number, '', $lastPickUp->pick_up_number);
                    } else {
                        $lastNumber = sprintf('%07d', 0);
                    }
                    $lastNumber = ((int) $lastNumber) + 1;
                    $pick_up_number .= sprintf('%07d', $lastNumber);

                    $pickUp->pick_up_number = $pick_up_number;
                    $pickUp->order_id = $order->id;
                    $pickUp->name = $delivery->type;
                    $pickUp->driver = '';
                    $pickUp->vehicle = '';
                    $pickUp->police_no = '';
                    $pickUp->created_by = Auth::user()->id;
                    $pickUp->save();

                    $order->pick_up_id = $pickUp->id;
                    $order->save();
                }

                $createdBy = User::where('id', '=', $order->created_by)->first();
                if ($createdBy->hasRole('client-orbit')) {
                    $items = DB::table('orders AS o')
                        ->join('order_items AS oi', 'o.id', '=', 'oi.order_id')
                        ->join('order_item_orbits AS oio', 'oi.id', '=', 'oio.order_item_id')
                        ->join('orbit_stocks AS os', 'oio.orbit_stock_id', '=', 'os.id')
                        ->join('routers AS r', 'os.router_id', '=', 'r.id')
                        ->join('po_dtl AS pd', 'r.po_dtl_id', '=', 'pd.id')
                        ->join('material AS m', 'pd.material_id', '=', 'm.id')
                        ->join('simcards AS s', 'os.simcard_id', '=', 's.id')
                        ->where('o.id', '=', $order->id)
                        ->select(['o.order_number', 'oi.sku', 'r.imei', 's.msisdn', 'm.name', 'o.id AS order_id', 'oi.id AS order_item_id', 'os.id AS orbit_stock_id', 'r.id AS router_id', 's.id AS simcard_id'])
                        ->get();
                    if (count($items) == 0) {
                        return redirect()->back()->with('alert.failed', 'Order Items is Empty');
                    }
                    // TODO hit api receive
                    $url = env('ORBIT_URL') . 'order/v1/shipments/mitracomm/webhook/handling';
                    $apiRequest = [
                        'type' => 'outbounds',
                        'channel' => 'MBSD',
                        'data' => [
                            'document_number' => $order->document_number,
                            'order_number' => $order->order_number,
                            'outbound_number' => $pickUp->pick_up_number,
                            'notes' => $order->notes,
                            'courier' => [
                                'name' => $pickUp->name,
                                'driver' => $pickUp->driver,
                                'vehicle' => $pickUp->vehicle,
                                'police_no' => $pickUp->police_no,
                            ]
                        ]
                    ];

                    $dataItems = [];
                    foreach ($items as $item) {
                        $dataItems[] = [
                            'sku' => $item->sku,
                            'name' => 'BUNDLING ROUTER ' . (str_replace("ZTE-", "", $item->name)) . '+ SIMCARD',
                            'qty' => 1,
                            'imei' => $item->imei,
                            'msisdn' => $item->msisdn
                        ];
                    }
                    $apiRequest['data']['items'] = $dataItems;

                    $apiResponse = Http::post($url, $apiRequest);
                    if ($apiResponse->successful()) {
                        $pickUp->save();
                        $order->status_id = 6;
                        $order->save();

                        foreach ($items as $item) {
                            $orbitStock = OrbitStock::findOrFail($item->orbit_stock_id);
                            $orbitStock->order_id = $order->id;
                            $orbitStock->order_item_id = $item->order_item_id;
                            $orbitStock->status_id = 6;
                            $orbitStock->save();
                        }
                    }
                    $apiSendLog = new ApiSendLog();
                    $apiSendLog->header = json_encode($apiResponse->headers());
                    $apiSendLog->ip = '::1';
                    $apiSendLog->url = $url;
                    $apiSendLog->method = 'POST';
                    $apiSendLog->status_code = $apiResponse->status();
                    $apiSendLog->request = json_encode($apiRequest);
                    $apiSendLog->response = json_encode($apiResponse->json());
                    $apiSendLog->data_internal = json_encode([
                        'order_id' => $order->id
                    ]);
                    $apiSendLog->created_by = Auth::user()->id;
                    $apiSendLog->save();
                } else {
                    $pickUp->save();
                    $order->status_id = 6;
                    $order->save();
                }
            }
        }
    }
}
