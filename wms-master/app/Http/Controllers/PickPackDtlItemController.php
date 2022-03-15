<?php

namespace App\Http\Controllers;

use App\Models\OrbitStock;
use App\Models\OrderItemOrbit;
use App\Models\Router;
use App\Models\Simcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PickPackDtlItemController extends Controller
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
        $orbitStock = OrbitStock::findOrFail($request->orbit_stock_id);
        if ($orbitStock->status_id != 2) {
            return response()->json(['msg' => 'Stock Status is ' . $orbitStock->status->name], 400);
        }

        $model = new OrderItemOrbit();
        $model->order_item_id = $request->order_item_id;
        $model->orbit_stock_id = $request->orbit_stock_id;
        $model->created_by = Auth::user()->id;
        $model->save();

        $orbitStock->status_id = 4;
        $orbitStock->save();

        $router = Router::findOrFail($orbitStock->router_id);
        $router->status_id = 4;
        $router->save();

        $simcard = Simcard::findOrFail($orbitStock->simcard_id);
        $simcard->status_id = 4;
        $simcard->save();

        return response()->json(['id' => $model->id]);
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
}
