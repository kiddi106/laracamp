<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PickUpController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $erroMsgs = [];
        $totalRow = 0;
        $requireds = ['type', 'channel', 'data'];
        foreach ($requireds as $field) {
            if (!$request->$field) {
                $erroMsgs[$field] = 'required field';
            }
        }

        if ($request->data) {
            $data = $request->data;
            $requireds = ['document_number', 'order_number', 'outbound_number', 'status', 'date', 'courier', 'items'];
            foreach ($requireds as $field) {
                if (!isset($data['$field'])) {
                    $erroMsgs[$field] = 'required field';
                }

                if ($field == 'courier') {
                    $courierObjects = ['name', 'driver', 'vehicle'];
                    foreach ($courierObjects as $courierObject) {
                        if (!isset($data[$courierObject])) {
                            $erroMsgs[$field][$courierObject] = 'required field';
                        }
                    }
                }

                if ($field == 'items') {
                    if (!isset($request->$field)) {
                        $erroMsgs[$field] = 'required field';
                    } else {
                        if (!is_array($request->$field)) {
                            $erroMsgs[$field] = 'must be array';
                        } else {
                            $totalRow = count($request->$field);
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
                                if (!isset($item['name'])) {
                                    $erroMsgs[$field][$key]['name'] = 'required field';
                                }
                                if (!isset($item['imei'])) {
                                    $erroMsgs[$field][$key]['imei'] = 'required field';
                                }
                                if (!isset($item['msisdn'])) {
                                    $erroMsgs[$field][$key]['msisdn'] = 'required field';
                                }
                            }
                        }
                    }
                }
            }
        }

        if (count($erroMsgs) > 1) {
            // TODO insert log
            // TODO insert delivery
            return response()->json([
                'status' => false,
                'code' => 400,
                'type' => 'delivery',
                'total_row' => $totalRow
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
}
