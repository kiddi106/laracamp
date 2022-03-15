<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderPickUp;
use App\Models\OrderReceiver;
use App\Models\PurchaseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly order resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $erroMsgs = [];
        $requireds = ['type', 'customer_number', 'document_number', 'awb', 'delivery', 'items', 'receiver'];
        $purchase_type_id = null;
        $order_number = null;
        foreach ($requireds as $field) {
            if (!$request->$field) {
                $erroMsgs[$field] = 'required field';
            }

            if ($field == 'document_number' && $request->$field) {
                $order = Order::query()->where('document_number', '=', trim($request->$field))->first();
                if ($order) {
                    $order_number = $order->order_number;
                    $erroMsgs[$field] = 'document number has been created';
                }
            } elseif ($field == 'type' && $request->$field) {
                $purchase_type = PurchaseType::query()->where('name', '=', strtoupper($request->$field))->first();
                if ($purchase_type) {
                    $purchase_type_id = $purchase_type->id;
                } else {
                    $erroMsgs[$field] = 'not found';
                }
            } elseif ($field == 'delivery') {
                if (!isset($request->$field['type'])) {
                    $erroMsgs[$field]['type'] = 'required field';
                }
                if (!isset($request->$field['do_date'])) {
                    $erroMsgs[$field]['do_date'] = 'required field';
                } else {
                    if (date('Y-m-d H:i:s', strtotime($request->$field['do_date'])) != $request->$field['do_date']){
                        $erroMsgs[$field]['do_date'] = 'must be date';
                    }
                }
            } elseif ($field == 'items') {
                if (!isset($request->$field)) {
                    $erroMsgs[$field] = 'required field';
                } else {
                    if (!is_array($request->$field)) {
                        $erroMsgs[$field] = 'must be array';
                    } else {
                        foreach ($request->$field as $key => $item) {
                            if (!isset($item['sku'])) {
                                $erroMsgs[$field][$key]['sku'] = 'required field';
                            }
                            if (!isset($item['qty'])) {
                                $erroMsgs[$field][$key]['qty'] = 'required field';
                            } else {
                                if (!is_int($item['qty'])) {
                                    $erroMsgs[$field][$key]['qty'] = 'must be int';
                                }
                            }
                        }
                    }
                }
            } elseif ($field == 'receiver') {
                if (!isset($request->$field)) {
                    $erroMsgs[$field] = 'required field';
                } else {
                    if (!isset($request->$field['name'])) {
                        $erroMsgs[$field]['name'] = 'required field';
                    }

                    if (!isset($request->$field['phone'])) {
                        $erroMsgs[$field]['phone'] = 'required field';
                    }

                    if (!isset($request->$field['postal_code'])) {
                        $erroMsgs[$field]['postal_code'] = 'required field';
                    }

                    if (!isset($request->$field['destination'])) {
                        $erroMsgs[$field]['destination'] = 'required field';
                    }
                }
            }
        }

        $header = getallheaders();
        if (is_array($header)) {
            $header = json_encode($header);
        }

        $apiLog = new ApiLog();
        $apiLog->header = $header;
        $apiLog->url = $request->url();
        $apiLog->method = $request->method();
        $apiLog->ip = $request->ip();
        $apiLog->request = json_encode($request->all());
        $apiLog->created_by = auth()->id();

        if (count($erroMsgs) > 0) {
            // TODO insert log
            $response = [
                'status' => false,
                'message' => 'error',
                'error' => $erroMsgs
            ];
            if ($order_number) {
                $response['order_number'] = $order_number;
            }
            $apiLog->response = json_encode($response);
            $apiLog->status_code = 400;
            $apiLog->created_by = auth('api')->id();
            $apiLog->save();
            return response()->json($response, $apiLog->status_code);
        }


        // TODO insert orders
        $order_number = 'WMS' . date('Ymd');
        $lastOrder = Order::query()->where('order_number', 'LIKE', $order_number . '%')->orderBy('id', 'desc')->first();
        $lastNumber = '';
        if ($lastOrder) {
            $lastNumber = str_replace($order_number, '', $lastOrder->order_number);
        } else {
            $lastNumber = sprintf('%07d', 0);
        }
        $lastNumber = ((int) $lastNumber) + 1;
        $order_number .= sprintf('%07d', $lastNumber);

        $order = new Order();
        $order->order_number = $order_number;
        $order->status_id = 1; // available
        $order->purchase_type_id = $purchase_type_id;
        $order->customer_number = $request->customer_number;
        $order->document_number = $request->document_number;
        $order->notes = $request->notes;
        $order->awb = $request->awb;
        $order->estimation_send_date = $request->estimation_send_date && date('Y-m-d H:i:s', strtotime($request->estimation_send_date)) == $request->estimation_send_date ? $request->estimation_send_date : null;
        $order->created_by = auth('api')->id();
        $order->save();

        // TODO insert order_delivery
        $delivery = new OrderDelivery();
        $delivery->order_id = $order->id;
        $delivery->type = $request->delivery['type'];
        $delivery->do_date = $request->delivery['do_date'];
        $delivery->created_by = auth('api')->id();
        $delivery->save();

        // TODO update order.delivery_id
        $order->delivery_id = $delivery->id;

        // TODO insert order_receiver
        $receiver = new OrderReceiver();
        $receiver->order_id = $order->id;
        $receiver->name = $request->receiver['name'];
        $receiver->phone = $request->receiver['phone'];
        $receiver->postal_code = $request->receiver['postal_code'];
        $receiver->destination = $request->receiver['destination'];
        $delivery->created_by = auth('api')->id();
        $receiver->save();

        // TODO update order.receiver_id
        $order->receiver_id = $receiver->id;

        // TODO insert items
        $items = [];
        foreach ($request->items as $item) {
            $items[] = [
                'order_id' => $order->id,
                'sku' => $item['sku'],
                'qty' => (int) $item['qty'],
                'created_by' => auth('api')->id(),
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        DB::table('order_items')->insert($items);

        $order->save();


        // TODO insert log
        $response = [
            'status' => true,
            'message' => 'success',
            'order_number' => $order->order_number,
        ];
        $apiLog->response = json_encode($response);
        $apiLog->status_code = 200;
        $apiLog->created_by = auth()->id();
        $apiLog->save();
        return response()->json($response, $apiLog->status_code);
    }

    /**
     * Update status pick up order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pickUp(Request $request)
    {
        $erroMsgs = [];
        $totalRow = 0;
        $requireds = ['type', 'data'];
        foreach ($requireds as $field) {
            if (!$request->$field) {
                $erroMsgs[$field] = 'required field';
            }
        }

        if ($request->data) {
            $data = $request->data;
            $requireds = ['document_number', 'order_number', 'outbound_number', 'date', 'courier', 'items'];
            foreach ($requireds as $field) {
                if (!isset($data[$field])) {
                    $erroMsgs['data'][$field] = 'required field';
                } else {
                    if ($field == 'courier') {
                        $courierObjects = ['name', 'driver', 'vehicle', 'police_no'];
                        foreach ($courierObjects as $courierObject) {
                            if (!isset($data[$field][$courierObject])) {
                                $erroMsgs['data'][$field][$courierObject] = 'required field';
                            }
                        }
                    } else if ($field == 'items') {
                        if (!isset($data[$field])) {
                            $erroMsgs['data'][$field] = 'required field';
                        } else {
                            if (!is_array($data[$field])) {
                                $erroMsgs['data'][$field] = 'must be array';
                            } else {
                                $totalRow = count($data[$field]);
                                foreach ($data[$field] as $key => $item) {
                                    $itemRequireds =['sku','qty','name','imei','msisdn'];
                                    foreach ($itemRequireds as $itemRequired) {
                                        if (!isset($item[$itemRequired])) {
                                            $erroMsgs['data'][$field][$key][$itemRequired] = 'required field';
                                        } elseif ($itemRequired == 'qty' && !is_int($item['qty'])) {
                                            $erroMsgs['data'][$field][$key]['qty'] = 'must be integer';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // foreach ($requireds as $field) {
            //     if (!isset($data[$field])) {
            //         $erroMsgs['data'][$field] = 'required field';
            //         continue;
            //     }

            //     if (!isset($erroMsgs['data']['courier']) && $field == 'courier') {
            //         $courierObjects = ['name', 'driver', 'vehicle'];
            //         foreach ($courierObjects as $courierObject) {
            //             if (!isset($data[$field][$courierObject])) {
            //                 $erroMsgs['data'][$field][$courierObject] = 'required field';
            //             }
            //         }
            //     }

            //     if ($field == 'items') {
            //         if (!isset($data[$field])) {
            //             $erroMsgs['data'][$field] = 'required field';
            //         } else {
            //             if (!is_array($request->$field)) {
            //                 $erroMsgs['data'][$field] = 'must be array';
            //             } else {
            //                 $totalRow = $request->$field;
            //                 foreach ($request->$field as $key => $item) {
            //                     if (!isset($item['sku'])) {
            //                         $erroMsgs['data'][$field][$key]['sku'] = 'required field';
            //                     }
            //                     if (!isset($item['qty'])) {
            //                         $erroMsgs['data'][$field][$key]['qty'] = 'required field';
            //                     } else {
            //                         if (!is_int($item['qty'])) {
            //                             $erroMsgs['data'][$field][$key]['qty'] = 'must be int';
            //                         }
            //                     }
            //                     if (!isset($item['name'])) {
            //                         $erroMsgs['data'][$field][$key]['name'] = 'required field';
            //                     }
            //                     if (!isset($item['imei'])) {
            //                         $erroMsgs['data'][$field][$key]['imei'] = 'required field';
            //                     }
            //                     if (!isset($item['msisdn'])) {
            //                         $erroMsgs['data'][$field][$key]['msisdn'] = 'required field';
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }
        }

        if (count($erroMsgs) > 0) {
            // TODO insert log
            // TODO insert delivery
            return response()->json([
                'status' => false,
                'code' => 400,
                'type' => 'delivery',
                'total_row' => $totalRow,
                'error_messages' => $erroMsgs
            ], 400);
        }

        // TODO insert log
        // TODO insert delivery
        return response()->json([
            'status' => true,
            'code' => 200,
            'type' => 'delivery',
            'total_row' => $totalRow
        ]);
    }

    public function inbound(Request $request)
    {
        if ($request->order_number) {
            $order = Order::query()->where('order_number', '=', $request->order_number)->first();
            if ($order) {
                $response = [];
                $response['order_number'] = $order->order_number;
                $response['status'] = strtolower($order->status->name);
                $response['date'] = date('Y-m-d H:i:s', strtotime($order->created_at));
                $response['type'] = strtolower($order->purchase_type->name);
                $response['customer_number'] = $order->customer_number;
                $response['document_number'] = $order->document_number;
                $response['notes'] = $order->notes;
                $response['awb'] = $order->awb;

                $delivery = $order->delivery;
                $response['delivery'] = [
                    'type' => $delivery->type,
                    'do_date' => date('Y-m-d H:i:s', strtotime($delivery->do_date))
                ];
                $response['estimation_send_date'] = $order->estimation_send_date? date('Y-m-d H:i:s', strtotime($order->estimation_send_date)) : null;

                $items = [];
                foreach ($order->items as $item) {
                    $items[] = [
                        'sku' => $item->sku,
                        'qty' => $item->qty
                    ];
                }
                $response['items'] = $items;

                $receiver = $order->receiver;
                $response['receiver'] = [
                    'name' => $receiver->name,
                    'phone' => $receiver->phone,
                    'postal_code' => $receiver->postal_code,
                    'destination' => $receiver->destination
                ];

                return response()->json($response, 200);
            }
        }
    }

    public function pick_up(Request $request)
    {
        $erroMsgs = [];
        $requireds = ['order_number', 'courier'];
        foreach ($requireds as $field) {
            if (!$request->$field) {
                $erroMsgs[$field] = 'required field';
            }

            if ($field == 'courier' && $request->$field) {
                $courierRequireds = ['name','driver','vehicle','police_no'];
                foreach ($courierRequireds as $courierRequired) {
                    if (!isset($request->$field[$courierRequired])) {
                        $erroMsgs[$field][$courierRequired] = 'required field';
                    }
                }
            }
        }

        $header = getallheaders();
        if (is_array($header)) {
            $header = json_encode($header);
        }

        $order = Order::query()->where('order_number', '=', $request->order_number)->first();
        if (!$order) {
            $erroMsgs['message'] = 'Order Number not Exists';
        } elseif ($order->status_id == 1) {
            $erroMsgs['message'] = 'Order is not ready to Pick Up';
        } elseif ($order->pick_up_id) {
            $pickUp = $order->pick_up;
            $erroMsgs['message'] = $order->status->name;
            $erroMsgs['pick_up'] = [
                'pick_up_number' => $pickUp->pick_up_number,
                'created_at' => date('Y-m-d H:i:s', strtotime($pickUp->created_at)),
                'courier' => [
                    'name' => $pickUp->name,
                    'driver' => $pickUp->driver,
                    'vehicle' => $pickUp->vehicle,
                    'police_no' => $pickUp->police_no,
                ]
            ];
        } elseif ($order->status_id != 4) {
            $erroMsgs['order_number'] = 'Order Number is not available to Pick Up';
        }

        $apiLog = new ApiLog();
        $apiLog->header = $header;
        $apiLog->url = $request->url();
        $apiLog->method = $request->method();
        $apiLog->ip = $request->ip();
        $apiLog->request = json_encode($request->all());
        $apiLog->created_by = auth()->id();

        if (count($erroMsgs) > 0) {
            // TODO insert log
            $response = [
                'status' => false,
                'message' => 'error',
                'error' => $erroMsgs
            ];
            $apiLog->response = json_encode($response);
            $apiLog->status_code = 400;
            $apiLog->created_by = auth('api')->id();
            $apiLog->save();
            return response()->json($response, $apiLog->status_code);
        }

        // TODO insert order_pick_up
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

        $pickUp = new OrderPickUp();
        $pickUp->pick_up_number = $pick_up_number;
        $pickUp->order_id = $order->id;
        $pickUp->name = $request->courier['name'];
        $pickUp->driver = $request->courier['driver'];
        $pickUp->vehicle = $request->courier['vehicle'];
        $pickUp->police_no = $request->courier['police_no'];
        $pickUp->created_by = auth('api')->id();
        $pickUp->save();

        $order->status_id = 5;
        $order->pick_up_id = $pickUp->id;
        $order->save();

        $response = [
            'message' => 'success',
            'pick_up_number' => $pick_up_number
        ];

        // TODO insert log
        $apiLog->response = json_encode($response);
        $apiLog->status_code = 200;
        $apiLog->created_by = auth('api')->id();
        $apiLog->save();
        return response()->json($response, $apiLog->status_code);
    }

    public function outbound(Request $request)
    {
        $status_code = 200;
        $response = [];
        if ($request->pick_up_number) {
            $pickUp = OrderPickUp::query()->where('pick_up_number', '=', $request->pick_up_number)->first();
            if ($pickUp) {
                $order = Order::query()->where('pick_up_id', '=', $pickUp->id)->first();
                if ($order) {
                    $_order = [];
                    $_order['order_number'] = $order->order_number;
                    $_order['status'] = strtolower($order->status->name);
                    $_order['date'] = date('Y-m-d H:i:s', strtotime($order->created_at));
                    $_order['type'] = strtolower($order->purchase_type->name);
                    $_order['customer_number'] = $order->customer_number;
                    $_order['document_number'] = $order->document_number;
                    $_order['notes'] = $order->notes;
                    $_order['awb'] = $order->awb;

                    if ($order->delivery) {
                        $delivery = $order->delivery;
                        $_order['delivery'] = [
                            'type' => $delivery->type,
                            'do_date' => date('Y-m-d H:i:s', strtotime($delivery->do_date))
                        ];
                    }

                    $_order['estimation_send_date'] = $order->estimation_send_date? date('Y-m-d H:i:s', strtotime($order->estimation_send_date)) : null;
                    $items = $order->items;
                    foreach ($items as $item) {
                        $_order['item'][] = ['sku' => $item->sku, 'qty' => $item->qty];
                    }

                    $receiver = $order->receiver;
                    if ($receiver) {
                        $_order['receiver'] = [
                            'name' => $receiver->name,
                            'phone' => $receiver->phone,
                            'postal_code' => $receiver->postal_code,
                            'destination' => $receiver->destination
                        ];
                    }

                    $response = [
                        'pick_up_number' => $pickUp->pick_up_number,
                        'status' => $order->status->name,
                        'picked_date' => $pickUp->picked_at ? date('Y-m-d H:i:s', strtotime($pickUp->picked_at)) : null,
                        'courier' => [
                            'name' => $pickUp->name,
                            'driver' => $pickUp->driver,
                            'vehicle' => $pickUp->vehicle,
                            'police_no' => $pickUp->police_no
                        ],
                        'order' => $_order
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'error',
                        'error' => ['pick_up_number' => 'order is not available']
                    ];
                }

            } else {
                $response = [
                    'status' => false,
                    'message' => 'error',
                    'error' => ['pick_up_number' => 'not exists']
                ];
            }
            return response()->json($response, 400);
        } else {
            $response = [
                'status' => false,
                'message' => 'error',
                'error' => ['pick_up_number' => 'required field']
            ];
        }
        return response()->json($response, $status_code);
    }

    public function list(Request $request)
    {
        $orderAll = Order::all();
        $orders = [];
        $response = ['total' => count($orderAll)];
        foreach ($orderAll as $order) {
            $_order = [];
            $_order['order_number'] = $order->order_number;
            $_order['status'] = strtolower($order->status->name);
            $_order['date'] = date('Y-m-d H:i:s', strtotime($order->created_at));
            $_order['type'] = strtolower($order->purchase_type->name);
            $_order['customer_number'] = $order->customer_number;
            $_order['document_number'] = $order->document_number;
            $_order['notes'] = $order->notes;
            $_order['awb'] = $order->awb;

            if ($order->delivery) {
                $delivery = $order->delivery;
                $_order['delivery'] = [
                    'type' => $delivery->type,
                    'do_date' => date('Y-m-d H:i:s', strtotime($delivery->do_date))
                ];
            }

            $_order['estimation_send_date'] = $order->estimation_send_date? date('Y-m-d H:i:s', strtotime($order->estimation_send_date)) : null;
            $items = $order->items;
            foreach ($items as $item) {
                $_order['item'][] = ['sku' => $item->sku, 'qty' => $item->qty];
            }

            $receiver = $order->receiver;
            if ($receiver) {
                $_order['receiver'] = [
                    'name' => $receiver->name,
                    'phone' => $receiver->phone,
                    'postal_code' => $receiver->postal_code,
                    'destination' => $receiver->destination
                ];
            }

            $pickUp = $order->pick_up;
            if ($pickUp) {
                $_order['courier'] = [
                    'name' => $pickUp->name,
                    'driver' => $pickUp->driver,
                    'vehicle' => $pickUp->vehicle,
                    'police_no' => $pickUp->police_no
                ];
            }

            $orders[] = $_order;
        }

        $response['orders'] = $orders;
        return response()->json($response, 200);
    }
}
