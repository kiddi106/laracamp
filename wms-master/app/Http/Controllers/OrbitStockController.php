<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrbitStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loadData(Request $request)
    {
        if ($request->imei) {
            $query = DB::table('orbit_stocks')
                        ->join('routers', 'orbit_stocks.router_id', '=', 'routers.id')
                        ->join('po_dtl', 'routers.po_dtl_id', '=', 'po_dtl.id')
                        ->join('material', 'po_dtl.material_id', '=', 'material.id')
                        ->join('simcards', 'orbit_stocks.simcard_id', '=', 'simcards.id')
                        ->join('purchase_types', 'orbit_stocks.purchase_type_id', '=', 'purchase_types.id')
                        ->where('routers.imei', '=', $request->imei)
                        ->select([
                            'orbit_stocks.*',
                            'routers.esn', 'routers.ssid', 'routers.password_router', 'routers.guest_ssid', 'routers.password_guest', 'routers.password_admin', 'routers.imei', 'routers.device_model', 'routers.device_type', 'routers.color',
                            'simcards.serial_no', 'simcards.msisdn', 'simcards.item_code', 'simcards.exp_at',
                            'purchase_types.name AS purchase_type_name',
                            'material.name AS material_name'
                        ]);
            if ($request->status_id) {
                $query->where('orbit_stocks.status_id', '=', 2);
            }

            $stock = $query->first();
            if ($stock) {
                return response()->json([$stock]);
            }
        }
        return response()->json([]);
    }
}
