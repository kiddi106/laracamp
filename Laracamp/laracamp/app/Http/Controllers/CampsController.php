<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Http\Requests\StoreCampsRequest;
use App\Http\Requests\UpdateCampsRequest;

class CampsController extends Controller
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
     * @param  \App\Http\Requests\StoreCampsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCampsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Camps  $camps
     * @return \Illuminate\Http\Response
     */
    public function show(Camps $camps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Camps  $camps
     * @return \Illuminate\Http\Response
     */
    public function edit(Camps $camps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCampsRequest  $request
     * @param  \App\Models\Camps  $camps
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCampsRequest $request, Camps $camps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Camps  $camps
     * @return \Illuminate\Http\Response
     */
    public function destroy(Camps $camps)
    {
        //
    }
}
