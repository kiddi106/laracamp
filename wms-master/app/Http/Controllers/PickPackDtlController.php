<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PickPackDtlController extends Controller
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
     * Datatables Ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        $query = OrderItem::query();
        if ($request->has('order_id')) {
            $query->where('order_id', '=', $request->order_id);
        }
        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('pickpack.dtl.action', ['model' => $model]);
            })
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $order_item_id
     * @return \Illuminate\Http\Response
     */
    public function showItems($order_item_id)
    {
        $model = OrderItem::findOrFail($order_item_id);
        return view('pickpack.dtl.items', ['model' => $model]);
    }
}
