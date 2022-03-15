<?php

namespace App\Http\Controllers;

use App\Models\Router;
use Illuminate\Http\Request;

class RouterController extends Controller
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
        $model = Router::findOrFail($id);
        return view('router.show', ['model' => $model]);
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

    public function loadData(Request $request)
    {
        if ($request->has('id') || $request->has('imei')) {
            $query = Router::query();

            if ($request->has('id')) {
                $query->where('id', '=', $request->id);
            }

            if ($request->has('imei')) {
                $query->where('imei', '=', $request->imei);
            }

            $columns = ['status_id', 'condition', 'purchase_type'];
            foreach ($columns as $column) {
                if ($request->has($column)) {
                    $query->where($column, '=', $request->$column);
                }
            }
            return response()->json(
                $query->get()
            ) ;
        }
        return response()->json([]);
    }
}
