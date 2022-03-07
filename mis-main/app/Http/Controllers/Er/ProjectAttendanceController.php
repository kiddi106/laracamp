<?php

namespace App\Http\Controllers\Er;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Er\Company;
use App\Models\Er\EmployeeShift;
use App\Models\Er\Project;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Er\ProjectAttendance;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ProjectAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::connection('MESDB')->table('employees as e')
        ->leftJoin('attendances as a','e.uuid','=','a.employee_uuid')
        ->where('e.department_code','CL1908300029-POS-262')
        ->where('date','2021-10-05');
        $data['companies'] = Company::all();

        return view('er.projectAttendance.index',$data);
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

    public function getProject(Request $request)
    {
        $company_id = $request->company_id;
        $project = Project::where('company_id',$company_id)->get();

        return response()->json($project);
    }
    public function tanggal($date)
    {
        $exp = explode('-', $date);
        $date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
        return $date;
    }
    public function dataTable(Request $request)
    {
        $project_code = $request->project;

        $date = explode(' to ', $request->date);
        $start = $this->tanggal($date[0]);
        $end = $this->tanggal($date[1]);
        $query = DB::connection('MESDB')->table('employees')
        ->leftJoin('attendances','employees.uuid','=','attendances.employee_uuid')
        ->where('employees.department_code', $project_code)->whereBetween('attendances.date', [$start, $end])
        ->select('attendances.*','employees.name');


        return Datatables::of($query)
            ->addColumn('loc_in', function ($model) {
                if ($model->location_in) {
                    return "<a href=" . route("attendance.location", ['location' => $model->location_in]) . " type='button' title='Location' class='btn btn-xs btn-secondary show-loc'>Location</button>";
                }
            })
            ->addColumn('loc_out', function ($model) {
                if ($model->location_out) {
                    return "<a href=" . route("attendance.location", ['location' => $model->location_out]) . " type='button' title='Location' class='btn btn-xs btn-secondary show-loc'>Location</button>";
                }
            })
            ->addColumn('tanggal', function ($model) {
                return date("d/m/Y", strtotime($model->date));
            })
            ->addColumn('name', function ($model) {
                return $model->name;
            })
            ->addColumn('in', function ($model) {
                return substr($model->time_in, 0, 5);
            })
            ->addColumn('out', function ($model) {
                return substr($model->time_out, 0, 5);
            })
            ->addColumn('shift', function ($model) {
                $shift = EmployeeShift::join('mst_shift as ms','ms.id','=','employee_shift.shift_id')->where('employee_uuid',$model->employee_uuid)->where('date',$model->date)->first();
                if ($shift) {
                    $shift_nm = $shift->shift_nm;
                }
                else
                {
                    $shift_nm = $shift;
                }
                return $shift_nm;
            })
            ->addColumn('sched_in', function ($model) {
                $shift = EmployeeShift::join('mst_shift as ms','ms.id','=','employee_shift.shift_id')->where('employee_uuid',$model->employee_uuid)->where('date',$model->date)->first();
                if ($shift) {
                    $shift_nm = substr($shift->sched_in, 0, 5);
                }
                else
                {
                    $shift_nm = '';
                }
                return $shift_nm;
            })
            ->addColumn('sched_out', function ($model) {
                $shift = EmployeeShift::join('mst_shift as ms','ms.id','=','employee_shift.shift_id')->where('employee_uuid',$model->employee_uuid)->where('date',$model->date)->first();
                if ($shift) {
                    $shift_nm = substr($shift->sched_out, 0, 5);
                }
                else
                {
                    $shift_nm = '';
                }
                return $shift_nm;
            })
            ->rawColumns(['loc_in', 'loc_out','tanggal','shift','sched_in','sched_out'])
            ->make(true);
    }

    public function exportEmployee($date,$project_code)
    {
        $project_code = $project_code;
        $date = explode(' to ', urldecode($date));
        $startdate = $this->tanggal($date[0]);
        $enddate = $this->tanggal($date[1]);

        $data = ProjectAttendance::leftJoin('employees as e','e.uuid','=','attendances.employee_uuid')
        ->leftJoin('departments as d','d.code','=','e.department_code')
        ->join('role_employee as re','re.employee_uuid','=','e.uuid')
        ->join('roles as r','r.id','=','re.role_id')
        ->where('attendances.department_code', $project_code)->whereBetween('attendances.date', [$startdate, $enddate])
        ->select('e.uuid','e.name as empl_name','e.empl_id','d.name as project','attendances.time_in','attendances.time_out','attendances.location_in','attendances.location_out','attendances.masker','attendances.hand_sanitizer','attendances.temperature','attendances.date','r.display_name as role')
        ->get();
        // dd($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'Employee Name');
        $sheet->setCellValue('C1', 'Project');
        $sheet->setCellValue('D1', 'Job Level');
        $sheet->setCellValue('E1', 'Shift');
        $sheet->setCellValue('F1', 'Date');
        $sheet->setCellValue('G1', 'Schedule In');
        $sheet->setCellValue('H1', 'Time In');
        $sheet->setCellValue('I1', 'Location In');
        $sheet->setCellValue('J1', 'Schedule Out');
        $sheet->setCellValue('K1', 'Time Out');
        $sheet->setCellValue('L1', 'Location Out');
        $sheet->setCellValue('M1', 'Masker');
        $sheet->setCellValue('N1', 'Hand Sanitizer');
        $sheet->setCellValue('O1', 'Body Temperature');
        $no = 2;
        foreach ($data as $key => $item) {
            $shift = EmployeeShift::join('mst_shift as ms','ms.id','=','employee_shift.shift_id')->where('employee_uuid',$item->uuid)->where('date',$item->date)->first();
            if ($shift) {
                    $shift_nm = $shift->shift_nm;
                    $sched_in = substr($shift->sched_in, 0, 5);
                    $sched_out = substr($shift->sched_out, 0, 5);
            }
            else
            {
                $shift_nm = '';
                $sched_in = '';
                $sched_out = '';
            }

            $sheet->setCellValue('A' . $no, $item->empl_id);
            $sheet->setCellValue('B' . $no, $item->empl_name);
            $sheet->setCellValue('C' . $no, $item->project);
            $sheet->setCellValue('D' . $no, $item->role);
            $sheet->setCellValue('E' . $no, $shift_nm);
            $sheet->setCellValue('F'.$no, date("d/m/Y", strtotime($item->date)));
            $sheet->getStyle('F'.$no)
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);

            $sheet->setCellValue('G' . $no, $sched_in);

            $sheet->setCellValue('H' . $no, $item->time_in);
            $sheet->getStyle('H'.$no)
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_TIME1);

            $sheet->setCellValue('I' . $no, $item->location_in);
            $sheet->setCellValue('J' . $no, $sched_out);

            $sheet->setCellValue('K' . $no, substr($item->time_out, 0, 8));
            $sheet->getStyle('K'.$no)
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_TIME4);

            $sheet->setCellValue('L' . $no, $item->location_out);
            $sheet->setCellValue('M' . $no, $item->masker);
            $sheet->setCellValue('N' . $no, $item->hand_sanitizer);
            $sheet->setCellValue('O' . $no, $item->temperature);
            

            // $spreadsheet->getActiveSheet()->getStyle('J'.$no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');
            $no++;
        }

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Employee Attendance.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
