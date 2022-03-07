<?php

namespace App\Http\Controllers\Ga;

use App\Http\Controllers\Controller;
use App\Models\Auth\Department;
use App\Models\Auth\Employee;
use App\Models\Mst\MstRoom;
use App\Models\Mst\MstArea;
use App\Models\Req\ReqRoom;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust\Laratrust;
use Illuminate\Support\Facades\Response;

class ReqRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $area_code = Auth::user()->area_cd;
        
        if ($area_code == 'ALL') 
        $where_area = ['JKT', 'SMG'];
        
        else if ($area_code == 'JKT')
        $where_area = ['JKT'];
        
        else if ($area_code == 'SMG')
        $where_area = ['SMG'];
        $data['areas'] = MstArea::whereIn('area_code', $where_area)->get();
        // dd($data);
        return view('ga.room.index', $data);
    }

    public function getArea(Request $request)
    {
        $area = $request->area;
        $area_code = Auth::user()->area_cd;

        if ($area_code == 'ALL') 
        $where_area = ['JKT', 'SMG'];
        
        else if ($area_code == 'JKT')
        $where_area = ['JKT'];
        
        else if ($area_code == 'SMG')
        $where_area = ['SMG'];
        
        $gedung = MstRoom::select('gedung' ,'description')->where('area', '=', $area)->whereIn('area', $where_area)->groupBy('gedung', 'description')->get();

        return response()->json($gedung);
    }

    public function getBuilding(Request $request)
    {
        $gedung = $request->gedung;
        $area_code = Auth::user()->area_cd;
        
        if ($area_code == 'ALL') 
        $where_area = ['JKT', 'SMG'];
        
        else if ($area_code == 'JKT')
        $where_area = ['JKT'];
        
        else if ($area_code == 'SMG')
        $where_area = ['SMG'];
        
        $room = MstRoom::select('code', 'name', 'capacity')->where('gedung', '=', $gedung)->whereIn('area', $where_area)->groupBy('code', 'name', 'capacity')->get();
        
        // dd($room);
        return response()->json($room );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $area_code = Auth::user()->area_cd;
        $laratrust = new Laratrust(app());

        if ($area_code == 'ALL') 
        $where_area = ['JKT', 'SMG'];
        
        else if ($area_code == 'JKT')
        $where_area = ['JKT'];
        
        else if ($area_code == 'SMG')
        $where_area = ['SMG'];

        $data['departments'] = Department::all();
        $data['employees'] = Employee::all();
        $data['areas'] = MstArea::whereIn('area_code', $where_area)->get();
        $data['gedungs'] = MstRoom::select('gedung' ,'description')->whereIn('area', $where_area)->groupBy('gedung', 'description')->get();
        $data['rooms'] = MstRoom::select('code', 'name', 'capacity')->whereIn('area', $where_area)->groupBy('code', 'name', 'capacity')->get();
        $data['model'] = new MstRoom();
        $data['disabled'] = '';
        // dd($data);

        return view('ga.room.form', $data);
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
            'phone'         => 'required|digits_between:10,13',
            'dept_code'     => 'required',
            'request_for'   => 'required',
            'room_code'     => 'required',
            'area_code'     => 'required',
            'gedung_code'   => 'required',
            'purpose'       => 'required|string',
            'req_start'     => 'required',
            'req_end'       => 'required'
        ]);

        $query = ReqRoom::where('room_code', $request->room_code)
                        ->where('status_id', '!=', 2)
                        ->whereRaw("req_start >= DATEADD(hour, -1, '". $request->req_start."')")
                        ->whereRaw("req_end <= DATEADD(hour, +1, '". $request->req_end."')")->first();

        if ($query) {
            return Response::json(['message' => 'This room has been booked at that time. Please consider rescheduling.'], 500);
        } else {
            $model = new ReqRoom();

            $phone      = $request->phone;
            $dept_code  = $request->dept_code;
            $req_for    = $request->request_for;
            $room_code  = $request->room_code;
            $purpose    = $request->purpose;
            $s_time     = $request->req_start;
            $e_time     = $request->req_end;

            $model['room_code']      = $room_code;
            $model['phone']          = $phone;
            $model['dept_code']      = $dept_code;
            $model['request_for']    = $req_for;
            $model['purpose']        = $purpose;
            $model['status_id']      = 1;
            $model['req_start']      = $s_time;
            $model['req_end']        = $e_time;

            $model->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id     = base64_decode($id);
        $model  = ReqRoom::where('id', '=', $id)->first();
        
        foreach ($model as $data)
            $json_data[] = [
                'id'            => $data->id,
                'title'         => $data->purpose,
                'request_for'   => $data->request_for,
                'start'         => date($data->req_start)
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
        $data['area'] = ReqRoom::find($id);
        $area_code = Auth::user()->area_cd;
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-req-room');

        if ($area_code == 'ALL') 
        $where_area = ['JKT', 'SMG'];
        
        else if ($area_code == 'JKT')
        $where_area = ['JKT'];
        
        else if ($area_code == 'SMG')
        $where_area = ['SMG'];

        $data['departments'] = Department::all();
        $data['employees'] = Employee::all();

        $data['areas'] = MstArea::whereIn('area_code', $where_area)->get();
        $data['gedungs'] = MstRoom::select('gedung' ,'description')->whereIn('area', $where_area)->groupBy('gedung', 'description')->get();
        $data['rooms'] = MstRoom::select('code', 'name', 'capacity')->whereIn('area', $where_area)->groupBy('code', 'name', 'capacity')->get();
        $data['model'] = ReqRoom::where('id', '=', $id)->first();
        // dd($data);

        $disabled = '';
        if ($data['model']->exists) {
            if ($data['model']->created_by === Auth::user()->uuid) {
                $disabled = '';
                
            } else { 
                if ($canUpdate) {
                    $disabled = '';
                }
                else {
                    $disabled = 'disabled';
                }
            }
        }
        else {
            $disabled = '';
        }
        $data['disabled'] = $disabled;
        
        
        return view('ga.room.form', $data);
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
            'phone'         => 'required|digits_between:10,13',
            'dept_code'     => 'required',
            'request_for'   => 'required',
            'room_code'     => 'required',
            'purpose'       => 'required|string',
            'req_start'     => 'required',
            'req_end'       => 'required'
        ]);

        $id = \base64_decode($id);

        $query = ReqRoom::where('id', '!=', $id)
                        ->where('room_code', $request->room_code)
                        ->where('status_id', '!=', 2)
                        ->whereRaw("req_start >= DATEADD(hour, -1, '". $request->req_start."')")
                        ->whereRaw("req_end <= DATEADD(hour, +1, '". $request->req_end."')")->first();

        if ($query) {
            return Response::json(['message' => 'This room has been booked at that time. Please consider rescheduling.'], 500);
        } else {
            $model = ReqRoom::where('id', $id)->first();

            $phone      = $request->phone;
            $dept_code  = $request->dept_code;
            $req_for    = $request->request_for;
            $room_code  = $request->room_code;
            $purpose    = $request->purpose;
            $s_time     = $request->req_start;
            $e_time     = $request->req_end;
            $r_status   = $request->room_status;

            $model['room_code']      = $room_code;
            $model['phone']          = $phone;
            $model['dept_code']      = $dept_code;
            $model['request_for']    = $req_for;
            $model['purpose']        = $purpose;
            $model['status_id']      = $r_status;
            $model['req_start']      = $s_time;
            $model['req_end']        = $e_time;

            $model->update();
        }

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

    public function search(Request $request)
    {   

        // $model      = ReqRoom::all();
        $model      = ReqRoom::where('room_code', $request->room_code)
                            ->where('req_start', '>=', date('Y-m-d', strtotime($request->start)))
                            ->where('req_end', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                            ->get();
        // $model      = ReqRoom::where('req_start', '>', date('Y-m-d H:i'))->get();

        $json_data[] = [];

        foreach ($model as $data)
            if ($data->status_id == 1) {
                $status = "BOOKED";

                $json_data[] = [
                    'id'            => $data->id,
                    'title'         => $data->purpose,
                    'request_for'   => $data->request_for,
                    // 'title'         => $status . " | " . $data->purpose,
                    'start'         => date($data->req_start),
                    'end'           => date($data->req_end),
                    'color'         => '#F7D708', // a non-ajax option
                    'textColor'     => 'black' // a non-ajax option
                ];
            } else {
                $status = "CANCELLED";

                $json_data[] = [
                    'id'            => $data->id,
                    'title'         => $data->purpose,
                    'request_for'   => $data->request_for,
                    // 'title'         => $status . " | " . $data->purpose,
                    'start'         => date($data->req_start),
                    'end'           => date($data->req_end),
                    'color'         => '#CE0000', // a non-ajax option
                    'textColor'     => 'white' // a non-ajax option
                ];
            }
            
        if (!is_null($json_data)) {
            return response()->json($json_data);
        } else {
            return null;
        }
    }
}
