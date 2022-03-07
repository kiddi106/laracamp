<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\AttendanceTask;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;

class AttendanceTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('asd');
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
        if (!$request->loc) 
        {
            return redirect()->route('attendance.index')->with('alert.failed', 'Failed, Please Allow to Access Location');
        }

        $loc = Crypt::decryptString($request->loc);

        $task = new AttendanceTask();
        $task->attendance_id = $request->attendance_id;
        $task->location = $loc;
        $task->note = $request->note;
        $task->progress = $request->progress;
        $task->created_at = date('Y-m-d H:i:s');
        $task->created_by = Auth::user()->uuid;

        $task->save();

        dd($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return DataTables::of(AttendanceTask::where('attendance_id','=',$id))
            ->addIndexColumn()
            ->make(true);
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

    public function form($id)
    {
        $tasks = AttendanceTask::where('attendance_id','=',$id)->get();
        return view('auth.attendances.task',compact('tasks','id'));
    }

    public function showTask($id)
    {
        return view('auth.attendances.showTask',compact('id'));
    }

}
