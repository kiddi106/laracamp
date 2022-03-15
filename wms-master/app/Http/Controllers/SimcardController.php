<?php

namespace App\Http\Controllers;

use App\Models\Simcard;
use Illuminate\Http\Request;

class SimcardController extends Controller
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

    public function loadData(Request $request)
    {
        if ($request->has('id') || $request->has('msisdn')) {
            $query = Simcard::query();

            if ($request->has('id')) {
                $query->where('id', '=', $request->id);
            }

            if ($request->has('msisdn')) {
                $query->where('msisdn', '=', $request->msisdn);
            }

            $columns = ['status_id', 'condition'];
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
