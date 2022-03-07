<?php

namespace App\Http\Controllers\Mst;

use App\Http\Controllers\Controller;
use App\Models\Auth\Department;
use App\Models\Auth\Employee;
use App\Models\Mst\MstRoom;
use App\Models\Mst\MstHoliday;
use App\Models\Req\ReqRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class MstHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['rooms'] = MstRoom::all();
        return view('mst.holiday.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['departments'] = Department::all();
        $data['employees'] = Employee::all();
        $data['rooms'] = MstRoom::all();
        $data['model'] = new MstRoom();
        return view('mst.holiday.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [

            'date'   => 'required',
            'type'     => 'required',
            'notes'       => 'required|string',

        ]);
        $date        = $this->tanggalDb($request->date);

        $query = MstHoliday::where('date', $date)->first();

        if ($query) {
            return Response::json(['message' => 'This date has been set.'], 500);
        } else {
            $model = new MstHoliday();

            $type  = $request->type;
            $notes    = $request->notes;

            $model['date']       = $date;
            $model['type']       = $type;
            $model['notes']      = $notes;

            $model->save();
        }
    }

    public function show($id)
    {
        $id     = base64_decode($id);
        $model  = MstHoliday::where('id', '=', $id)->first();
        
        foreach ($model as $data)
            $json_data[] = [
                'id'        => $data->id,
                'title'     => $data->notes,
                'start'     => $data->date
            ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['model'] = MstHoliday::where('id', '=', $id)->first();
        $data['model']->date = $this->tanggalView($data['model']->date);


            return view('mst.holiday.desc_room', $data);
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
            $this->validate($request, [
    
                'date'   => 'required',
                'type'     => 'required',
                'notes'       => 'required|string',
    
            ]);
            $date        = $this->tanggalDb($request->date);
    
            $query = MstHoliday::where('date', $date)->first();
    
            if ($query) {
                return Response::json(['message' => 'This date has been set.'], 500);
            } else {
                $model = MstHoliday::where('id', $id)->first();
    
                $type  = $request->type;
                $notes    = $request->notes;
    
                $model['date']       = $date;
                $model['type']       = $type;
                $model['notes']      = $notes;
    
                $model->save();
            }

    }


    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {   

        $model      = MstHoliday::get();

        $json_data[] = [];

        foreach ($model as $data)
        if ($data->type == 'HLDAY') {
            $json_data[] = [
                'id'            => $data->id,
                'title'         => $data->notes,
                'start'         => date($data->date),
                'color'         => 'red', // a non-ajax option
                'textColor'     => 'white', // a non-ajax option
            ];
        } else {
            $json_data[] = [
                'id'            => $data->id,
                'title'         => $data->notes,
                'start'         => date($data->date),
                'color'         => 'orange', // a non-ajax option
                'textColor'     => 'white', // a non-ajax option
            ];
        }

        if (!is_null($json_data)) {
            return response()->json($json_data);
        } else {
            return null;
        }
    }

    function tanggalDb($date)
    {
        $exp = explode('/',$date);
        $date = $exp[2].'-'.$exp[1].'-'.$exp[0];
        return $date;
    }

    function tanggalView($date)
    {
        $exp = explode('-',$date);
        $date = $exp[2].'/'.$exp[1].'/'.$exp[0];
        return $date;
    }

}
