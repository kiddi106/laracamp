<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Attendance;
use App\Models\Auth\C19;
use App\Models\Auth\Department;
use App\Services\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Laratrust\Laratrust;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Mst\EmployeeShift;

use App\Models\Auth\Employee as EmployeeModel;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        $today = date('Y-m-d');
        $employee_uuid = Auth::user()->uuid;
        $check_in = false;
        $check_out = false;
        $id_shift = false;
        $attendance_id = false;
        // $attendance = Attendance::where(array('date'=> $today,'employee_uuid'=>$employee_uuid))->whereNotNull('time_in')->first();
        $besok = date('Y-m-d', (strtotime('+1 day', strtotime($today))));
        $kemarin = date('Y-m-d', (strtotime('-1 day', strtotime($today))));

        $shift = EmployeeShift::whereBetween('date_in', [$kemarin, $today])->where('employee_uuid',$employee_uuid)->whereNull('attendance_id')->orderBy('date', 'asc')->first();

        // dd($shift);
        if ($shift) 
        {   
            $out = Attendance::where('employee_uuid','=',$employee_uuid)->whereNotNull('time_in')->whereNull('time_out')->whereBetween('date', [$kemarin, $besok])->first();
            if ($out) {
                $check_out = true;
                $attendance_id = $out->id;
            }
            else {
                $check_out = false;
                $check = EmployeeShift::where('employee_uuid','=',$employee_uuid)->whereBetween('date_in', [$kemarin, $today])->whereNull('attendance_id')->orderBy('date','asc')->first();
                if ($check) 
                {
                    $check_in = true;
                    $id_shift = $check->id;
                }
                else
                {
                    $out = Attendance::where('employee_uuid','=',$employee_uuid)->whereNotNull('time_in')->whereNull('time_out')->whereBetween('date', [$today, $besok])->first();
                    $check_in = false;
                    if ($out) {
                        $check_out = true;
                        $attendance_id = $out->id;
                    }
                    else {
                        $check_out = false;
                    }
                }
            }

        }
        else {
            $attendance = Attendance::where(array('date'=> $today,'employee_uuid'=>$employee_uuid))->first();
            if ($attendance) {
                // $check_out = true;
                // $attendance_id = $attendance->id;
                if ($attendance->time_out != null) {
                    $check_out = false;
                    $check_in = false;
                }
                else {
                    $check_out = true;
                    $attendance_id = $attendance->id;
                }
            }
            else
            {
                $check_in = true;
            }
        }
        
        return view('auth.attendances.index', [
            'check_in' => $check_in,
            'check_out' => $check_out,
            'id_shift' => $id_shift,
            'attendance_id' => $attendance_id
        ]);
    }

    public function dataTables(Request $request)
    {

        $date = explode(' - ', $request->date);
        $start = $this->tanggalDb($date[0]);
        $end = $this->tanggalDb($date[1]);

        return Datatables::of(Attendance::where('employee_uuid', '=', Auth::user()->uuid)->whereBetween('date', [$start, $end]))
            ->addColumn('loc_in', function ($model) {
                if ($model->location_in) {
                    return "<a href=" . route("attendance.location", ['location' => $model->location_in]) . " type='button' title='Location' class='btn btn-secondary btn-xs show-loc'>Location</button>";
                }
            })
            ->addColumn('loc_out', function ($model) {
                if ($model->location_out) {
                    return "<a href=" . route("attendance.location", ['location' => $model->location_out]) . " type='button'title='Location'  class='btn btn-secondary btn-xs show-loc'>Location</button>";
                }
            })
            ->addColumn('tanggal', function ($model) {
                return date("d/m/Y", strtotime($model->date));
            })
            ->addColumn('in', function ($model) {
                return substr($model->time_in, 0, 5);
            })
            ->addColumn('out', function ($model) {
                return substr($model->time_out, 0, 5);
            })
            ->rawColumns(['loc_in', 'loc_out'])
            ->make(true);
    }

    function tanggalDb($date)
    {
        $exp = explode('/', $date);
        $date = $exp[2] . '-' . $exp[0] . '-' . $exp[1];
        return $date;
    }

    public function store(Request $request)
    {
        if (!$request->loc) {
            return redirect()->route('attendance.index')->with('alert.failed', 'Failed, Please Allow to Access Location');
        }


        $today = date('Y-m-d');
        $employee_uuid = Auth::user()->uuid;
        // $cek = Attendance::where(array('date'=> $today,'employee_uuid'=>$employee_uuid))->first();
        $loc = Crypt::decryptString($request->loc);

        // if ($request->id_shift)
        // {
        // }
        $shift = EmployeeShift::find($request->id_shift);

        if ($shift) {
            $tanggal_cek = $shift->date;
            $date = $shift->date;
            $shift_cd = $shift->shift_cd;
        } else {
            $tanggal_cek = $today;
            $date = $today;
            $shift_cd = 'SHF-R';
        }
        $cek = Attendance::where(array('date' => $tanggal_cek, 'employee_uuid' => $employee_uuid))->first();

        if ($cek !== null) {
            return redirect()->route('attendance.index')->with('alert.failed', 'You Already Check In');
        } else {
            $attendance = new Attendance();
            $attendance->employee_uuid = Auth::id();
            $attendance->date = $date;
            $attendance->date_in = $today;
            $attendance->time_in = date('H:i:s');
            $attendance->location_in = $loc;
            $attendance->masker = $request->masker;
            $attendance->hand_sanitizer = $request->hand_sanitizer;
            $attendance->temperature = $request->temperature;
            $attendance->shift_cd = $shift_cd;

            $attendance->save();

            if ($shift) {
                $shift->attendance_id = $attendance->id;
                $shift->save();
            }
        }

        return redirect()->route('attendance.index')->with('alert.success', 'Thank You for Attend Today');
    }

    public function show()
    {
        return view('auth.attendances.employee');
    }

    public function table_emp(Request $request)
    {
        $date = explode(' to ', $request->date);
        $start = $this->tanggal($date[0]);
        $end = $this->tanggal($date[1]);

        // $laratrust = new Laratrust(app());
        // $role = $laratrust->user()->roles;
        // foreach ($role as $key => $value) {
        //     $roleId[$key] = $value->id;
        // }
        $laratrust = new Laratrust(app());
        $role = $laratrust->user()->roles;
        // $data = Employee::getEmployee($startdate, $enddate, $role);

        return Datatables::of(Employee::getEmployee($start, $end, $role))
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
            ->addColumn('task', function ($model) {
                return "<a href=" . route("attendance.task.showTask", ['id' => $model->id]) . " type='button' title='Daily Task' class='btn btn-xs btn-primary show-loc'>Daily Task</button>";
            })
            ->addColumn('tanggal', function ($model) {
                return date("d/m/Y", strtotime($model->date));
            })
            ->addColumn('in', function ($model) {
                return substr($model->time_in, 0, 5);
            })
            ->addColumn('out', function ($model) {
                return substr($model->time_out, 0, 5);
            })
            ->rawColumns(['loc_in', 'loc_out', 'task'])
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        if (!$request->loc) {
            return redirect()->route('attendance.index')->with('alert.failed', 'Failed, Please Allow to Access Location');
        }
        $loc = Crypt::decryptString($request->loc);

        $attendance = Attendance::findOrFail($id);
        $attendance->time_out = date('H:i:s');
        $attendance->date_out = date('Y-m-d');
        $attendance->location_out = $loc;

        $attendance->save();
        return redirect()->route('attendance.index')->with('alert.success', 'Thank you for today');
    }

    public function dataTable(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Attendance::query();
        if ($startDate && $endDate) {
            $query->where('date', '>=', $startDate);
            $query->where('date', '<=', $endDate);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function location($loc)
    {
        return view('auth.attendances.location', ['loc' => $loc]);
    }
    public function getLocation(Request $request)
    {

        $loc = Crypt::encryptString($request->loc);

        return $loc;
    }

    public function report()
    {
        return view('auth.attendances.report');
    }

    public function tanggal($date)
    {
        $exp = explode('-', $date);
        $date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
        return $date;
    }

    public function exportExcel($date)
    {
        $date = explode(' to ', urldecode($date));
        $startdate = $this->tanggal($date[0]);
        $enddate = $this->tanggal($date[1]);

        $query = Attendance::query();
        $query->join('role_employee as re', 're.employee_uuid', '=', 'attendances.employee_uuid')
            ->join('employees as e', 'e.uuid', '=', 'attendances.employee_uuid')
            ->join('roles as r', 're.role_id', '=', 'r.id')
            ->join('departments as d', 'd.code', '=', 'r.department_code')
            ->whereBetween('date', [$startdate, $enddate])
            ->orderBy('attendances.date', 'asc')
            ->orderBy('attendances.time_in', 'asc')

            ->select('d.name as department', 'r.display_name as role', 'attendances.*', 'e.*');
        $data = $query->get();
        // dd($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'Employee Name');
        $sheet->setCellValue('C1', 'Department');
        $sheet->setCellValue('D1', 'Job Title');
        $sheet->setCellValue('E1', 'Date');
        $sheet->setCellValue('F1', 'Time In');
        $sheet->setCellValue('G1', 'Location In');
        $sheet->setCellValue('H1', 'Time Out');
        $sheet->setCellValue('I1', 'Location Out');
        $sheet->setCellValue('K1', 'Masker');
        $sheet->setCellValue('L1', 'Hand Sanitizer');
        $sheet->setCellValue('M1', 'Body Temperature');
        $no = 2;
        foreach ($data as $key => $item) {
            $sheet->setCellValue('A' . $no, $item->empl_id);
            $sheet->setCellValue('B' . $no, $item->name);
            $sheet->setCellValue('C' . $no, $item->department);
            $sheet->setCellValue('D' . $no, $item->role);
            $sheet->setCellValue('E' . $no, date("d/m/Y", strtotime($item->date)));
            $sheet->setCellValue('F' . $no, substr($item->time_in, 0, 5));
            $sheet->setCellValue('G' . $no, $item->location_in);
            $sheet->setCellValue('H' . $no, substr($item->time_out, 0, 5));
            $sheet->setCellValue('I' . $no, $item->location_out);
            $sheet->setCellValue('K' . $no, $item->masker);
            $sheet->setCellValue('L' . $no, $item->hand_sanitizer);
            $sheet->setCellValue('M' . $no, $item->temperature);

            $no++;
        }

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Employee Attendance.xls"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function exportExcel2($date)
    {

        $date = explode(' to ', urldecode($date));
        $startdate = $this->tanggal($date[0]);
        $enddate = $this->tanggal($date[1]);

        $divisions = [];
        $bod = Department::query()->where('code', '=', 'BOD')->first();
        $divisions[$bod->name] = $this->employeeAttendanceByDeptCodes(['BOD'], $startdate, $enddate);

        $departmens = Department::query()->where('parent_code', '=', 'BOD')->get();
        foreach ($departmens as $departmen) {
            $divisions[$departmen->name] = $this->employeeAttendanceByParrent($departmen->code, $startdate, $enddate);
        }

        $spreadsheet = new Spreadsheet();
        $idxSheet = 0;
        foreach ($divisions as $name => $attendances) {
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $name);
            $sheet->setCellValue('A1', 'Employee ID');
            $sheet->setCellValue('B1', 'Employee Name');
            $sheet->setCellValue('C1', 'Department');
            $sheet->setCellValue('D1', 'Job Title');
            $sheet->setCellValue('E1', 'Date');
            $sheet->setCellValue('F1', 'Time In');
            $sheet->setCellValue('G1', 'Location In');
            $sheet->setCellValue('H1', 'Time Out');
            $sheet->setCellValue('I1', 'Location Out');
            $sheet->setCellValue('K1', 'Masker');
            $sheet->setCellValue('L1', 'Hand Sanitizer');
            $sheet->setCellValue('M1', 'Body Temperature');
            $no = 2;

            foreach ($attendances as $item) {
                $sheet->setCellValue('A' . $no, $item->empl_id);
                $sheet->setCellValue('B' . $no, $item->name);
                $sheet->setCellValue('C' . $no, $item->department);
                $sheet->setCellValue('D' . $no, $item->role);
                $sheet->setCellValue('E' . $no, date("d/m/Y", strtotime($item->date)));
                $sheet->setCellValue('F' . $no, substr($item->time_in, 0, 5));
                $sheet->setCellValue('G' . $no, $item->location_in);
                $sheet->setCellValue('H' . $no, substr($item->time_out, 0, 5));
                $sheet->setCellValue('I' . $no, $item->location_out);
                $sheet->setCellValue('K' . $no, $item->masker);
                $sheet->setCellValue('L' . $no, $item->hand_sanitizer);
                $sheet->setCellValue('M' . $no, $item->temperature);

                $no++;
            }
            $spreadsheet->addSheet($sheet, $idxSheet);
            $idxSheet++;
        }

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Employee Attendance2.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function exportEmployee($date)
    {
        ob_end_clean(); // this
        ob_start(); // and this
        set_time_limit(0);
        $date = explode(' to ', urldecode($date));
        $startdate = $this->tanggal($date[0]);
        $enddate = $this->tanggal($date[1]);

        $laratrust = new Laratrust(app());
        $role = $laratrust->user()->roles;
        $data = Employee::getEmployee($startdate, $enddate, $role);
        // dd($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'Employee Name');
        $sheet->setCellValue('C1', 'Department');
        $sheet->setCellValue('D1', 'Job Level');
        $sheet->setCellValue('E1', 'Direct Leader');
        $sheet->setCellValue('F1', 'Date');
        $sheet->setCellValue('G1', 'Time In');
        $sheet->setCellValue('H1', 'Location In');
        $sheet->setCellValue('I1', 'Time Out');
        $sheet->setCellValue('J1', 'Location Out');
        $sheet->setCellValue('K1', 'Masker');
        $sheet->setCellValue('L1', 'Hand Sanitizer');
        $sheet->setCellValue('M1', 'Body Temperature');
        $no = 2;
        foreach ($data as $key => $item) {
            $sheet->setCellValue('A' . $no, $item->empl_id);
            $sheet->setCellValue('B' . $no, $item->emp_name);
            $sheet->setCellValue('C' . $no, $item->department_name);
            $sheet->setCellValue('D' . $no, $item->display_name);
            $sheet->setCellValue('E' . $no, $item->direct_leader);
            $sheet->setCellValue('F' . $no, date("d/m/Y", strtotime($item->date)));
            $sheet->setCellValue('G' . $no, substr($item->time_in, 0, 5));
            $sheet->setCellValue('H' . $no, $item->location_in);
            $sheet->setCellValue('I' . $no, substr($item->time_out, 0, 5));
            $sheet->setCellValue('J' . $no, $item->location_out);
            $sheet->setCellValue('K' . $no, $item->masker);
            $sheet->setCellValue('L' . $no, $item->hand_sanitizer);
            $sheet->setCellValue('M' . $no, $item->temperature);
            // $spreadsheet->getActiveSheet()->getStyle('J'.$no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');
            $no++;
        }

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Employee Attendance.xls"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function c19(Request $request)
    {
        if (!$request->loc) {
            return redirect()->route('attendance.index')->with('alert.failed', 'Failed, Please Allow to Access Location');
        }

        $loc = Crypt::decryptString($request->loc);

        $c19 = new C19();
        $c19->id_attendance = $request->id_attendance;
        $c19->location = $loc;
        $c19->masker = $request->masker;
        $c19->hand_sanitizer = $request->hand_sanitizer;
        $c19->temperature = $request->temperature;
        $c19->created_at = date('Y-m-d H:i:s');
        $c19->created_by = Auth::user()->uuid;

        $c19->save();
        return redirect()->route('attendance.index')->with('alert.success', 'Condition Has Been Updated');
    }

    public function c19Show($id)
    {
        return DataTables::of(c19::where('id_attendance', '=', $id))
            ->addIndexColumn()
            ->make(true);
    }

    public function exportNew($date)
    {
        // $startDate = strtotime('2020-06-01');
        // $endDate = strtotime('2020-06-30');
        $date = explode(' to ', urldecode($date));
        $startDate = strtotime($this->tanggal($date[0]));
        $endDate = strtotime($this->tanggal($date[1]));
        $dates = [];

        // column header
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $styleHeader = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'font' => [
                'bold' => true
            ]
        ];
        $sheet->setCellValueByColumnAndRow(1, 1, 'ID')->mergeCells('A1:A2');
        $sheet->getColumnDimension('A')->setWidth(18); //->setAutoSize(true)
        $sheet->getStyle('A1:A2')->getFont()->setName('Arial');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Name')->mergeCells('B1:B2');
        $sheet->getColumnDimension('B')->setWidth(27); //->setAutoSize(true)
        $sheet->setCellValueByColumnAndRow(3, 1, 'Role')->mergeCells('C1:C2');
        $sheet->getColumnDimension('C')->setWidth(21); //->setAutoSize(true)
        $sheet->setCellValueByColumnAndRow(4, 1, 'Departement')->mergeCells('D1:D2');
        $sheet->getColumnDimension('D')->setWidth(29); //->setAutoSize(true)
        $sheet->getStyle('A1:D2')->applyFromArray($styleHeader);
        $currentDate = $startDate;
        $i = 5;
        $columnIn = '';
        $columnOut = '';
        while ($currentDate <= $endDate) {
            $sheet->setCellValueByColumnAndRow($i, 1, date('Y-m-d', $currentDate));
            $columnIn = $sheet->getColumnDimensionByColumn($i)->getColumnIndex();
            $sheet->setCellValueByColumnAndRow($i, 2, 'IN')->getStyle($columnIn . '2')->getAlignment()->setHorizontal('center');
            $columnOut = $sheet->getColumnDimensionByColumn($i + 1)->getColumnIndex();
            $sheet->setCellValueByColumnAndRow($i + 1, 2, 'OUT')->getStyle($columnOut . '2')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells($columnIn . '1:' . $columnOut . '1');
            $sheet->getStyle($columnIn . '1:' . $columnOut . '1')->applyFromArray($styleHeader);
            $dates[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime("+1 day", $currentDate);
            $i = $i + 2;
        }

        // dd($employee = AuthEmployee::where('email', '=', 'fath.hadzami@mitracomm.com')->first());
        // $attendances = Attendance::where('employee_uuid', '=', '$employee->uuid')
        // $attendances = Attendance::where('date', '>=', date('Y-m-d', $startDate))
        //     ->where('date', '<=', date('Y-m-d', $endDate))
        //     ->get(['date', 'time_in', 'time_out'])
        //     ->keyBy('date');
        // dd($attendances);
        $query = EmployeeModel::join('role_employee as re', 're.employee_uuid', '=', 'employees.uuid')
            // ->where('employee_uuid', '=', '6D3689C7-FB7D-4505-8F9A-F5AF265D8EFF')
            ->whereNotIn('employees.uuid', ['F1F64912-6E8D-4784-9815-5CA71170329E', 'A738B637-D0A6-42BC-BA90-0F65FD3D9025', 'D3587F00-2422-4361-9141-D71D761039BD', '20663A1B-47C8-4C65-9FA5-A0FA95E3F4A8', '2D4BD3B6-CC5C-4765-8B1E-A2F5FFDD1E2F', '5208438F-6BA0-4903-BBFA-68BD2FF46447'])
            ->join('roles as r', 'r.id', '=', 're.role_id')
            ->join('departments as d', 'r.department_code', '=', 'd.code')
            ->select('employees.uuid', 'employees.name', 'employees.empl_id', 'r.name as role', 'd.name as department')
            ->get();

        $currentDate = $startDate;
        // $sheet->setCellValueByColumnAndRow(2, $j, 'Fath Hadzami');
        // $sheet->setCellValueByColumnAndRow(1, $j, $employee->name);
        $attendIn = '';
        $attendOut = '';
        $j = 0;
        // $schedIn = strtotime('08:00:00.0000000');
        $timeIn = '';
        $timeOut = '';
        foreach ($query as $key => $employee) {
            $i = 5;
            $j = $key + 3;
            $sheet->setCellValueByColumnAndRow(1, $j, $employee->empl_id)->getStyle('A3:A' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
            $sheet->setCellValueByColumnAndRow(2, $j, $employee->name)->getStyle('B3:B' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
            $sheet->setCellValueByColumnAndRow(3, $j, $employee->role)->getStyle('C3:C' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
            $sheet->setCellValueByColumnAndRow(4, $j, $employee->department)->getStyle('D3:D' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
            // $attendances = Attendance::where('attendances.employee_uuid', '=', 'E642DD65-1E77-40D9-9A83-06914D066BD9')
            $attendances = Attendance::where('attendances.employee_uuid', '=', $employee->uuid)
                ->where('attendances.date', '>=', date('Y-m-d', $startDate))
                ->where('attendances.date', '<=', date('Y-m-d', $endDate))
                // ->leftJoin('mst_shift as d', 'attendances.shift_cd', '=', 'd.shift_cd')
                // ->leftJoin('employee_shift as e', 'attendances.id', '=', 'e.attendance_id')
                // , 'e.date_in as edate_in', 'd.sched_in', 'e.date_out as edate_out', 'd.sched_out'
                ->get(['attendances.date', 'attendances.date_in', 'attendances.time_in', 'attendances.date_out', 'attendances.time_out', 'attendances.shift_cd', 'attendances.employee_uuid'])
                ->keyBy('date');
            foreach ($dates as $date) {
                $attendIn = $sheet->getColumnDimensionByColumn($i)->getColumnIndex();
                $attendOut = $sheet->getColumnDimensionByColumn($i + 1)->getColumnIndex();

                if (date("l", strtotime($date)) == 'Saturday' || date("l", strtotime($date)) == 'Sunday') {
                    $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
                    $sheet->getStyle($attendOut . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
                }

                if ($attendances->has($date)) {
                    $attendance = $attendances[$date];
                    $shift = EmployeeShift::join('mst_shift as ms', 'employee_shift.shift_cd', '=', 'ms.shift_cd')
                        ->where('employee_shift.date', '=', $date)
                        ->where('employee_shift.employee_uuid', '=', $attendance->employee_uuid)
                        ->select('employee_shift.date_in as edate_in', 'ms.sched_in', 'employee_shift.date_out as edate_out', 'ms.sched_out')
                        ->first();
                    // $schedIn = ($attendance->sched_in) ? strtotime($attendance->sched_in) : null;
                    // $schedOut = ($attendance->sched_out) ? strtotime($attendance->sched_out) : null;
                    // $eDateIn = strtotime($attendance->date);
                    // $eDateOut = strtotime($attendance->date);
                    if ($shift) {
                        $schedIn = ($shift->sched_in) ? ($shift->sched_in) : '08:00:00.0000000';
                        $schedOut = ($shift->sched_out) ? ($shift->sched_out) : '17:00:00.0000000';
                    } else {
                        $schedIn = '08:00:00.0000000';
                        $schedOut = '17:00:00.0000000';
                    }
                    $eDateIn = ($attendance->date);
                    $eDateOut = ($attendance->date);
                    $eSchedIn = $eDateIn . ' ' . $schedIn;
                    $eSchedOut = $eDateOut . ' ' . $schedOut;
                    $sheet->getStyle($attendIn)->getAlignment()->setVertical('center')->setHorizontal('center');
                    $sheet->setCellValueByColumnAndRow($i, $j, substr($attendance->time_in, 0, 5));
                    $sheet->getStyle($attendOut)->getAlignment()->setVertical('center')->setHorizontal('center');
                    $sheet->setCellValueByColumnAndRow($i + 1, $j, substr($attendance->time_out, 0, 5));
                    // $dateIn = ($attendance->date_in) ? strtotime($attendance->date_in) : strtotime($attendance->date);
                    $dateIn = ($attendance->date_in) ? ($attendance->date_in) : ($attendance->date);
                    $timeIn = ($attendance->time_in);
                    $timeOut = ($attendance->time_out);
                    // $timeIn = strtotime($attendance->time_in);
                    $aTimeIn = $dateIn . " " . $timeIn;
                    // $diffTimeIn = $timeIn - $schedIn; //pake strtotime
                    // $diffTimeIn = $aTimeIn - $eSchedIn;
                    // $diffJam    = floor($diffTimeIn / (60 * 60));
                    // $diffSisaMenit    = $diffTimeIn - $diffJam * (60 * 60); //sisa menit
                    // $diffJam    = floor($diffTimeIn / (60 * 60));
                    // $diffMenit    = floor($diffTimeIn / 60);
                    $time = new DateTime($eSchedIn);
                    $diff = $time->diff(new DateTime($aTimeIn));
                    $diffMenit = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                    // dd($schedIn, $schedOut, $eDateIn, $eDateOut, $eSchedIn, $eSchedOut, $aTimeIn, $diffMenit, $diff->format("%R"));
                    if (date("l", strtotime($date)) == 'Saturday' || date("l", strtotime($date)) == 'Sunday') {
                        if ($timeIn || $timeIn == '') {
                            $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                        }
                        if ($timeOut || $timeOut == '') {
                            $sheet->getStyle($attendOut . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                        }
                    }

                    if ($timeIn) {
                        if ($diff->format("%R") == '+' && $diffMenit > 10 && $schedIn) {
                            $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FBDE00');
                        }
                    } else {
                        $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');
                    }

                    if (!$timeOut) {
                        $sheet->getStyle($attendOut . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');
                    }
                }
                $i = $i + 2;
            }
        }
        $sheet->getStyle('A1:' . $attendOut . $j)->applyFromArray($styleArray);
        // $sheet->getStyle('A10')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Employee Attendance.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    private function allChildDepartmentCode($parent_code)
    {
        $retval = [];
        if ($parent_code != null) {
            $departments = Department::query()->where('parent_code', '=', $parent_code)->get();
            if ($departments) {
                $retval[] = $parent_code;
                foreach ($departments as $department) {
                    $retval[] = $department->code;

                    $childs = $this->allChildDepartmentCode($department->code);
                    if (count($childs) > 0) {
                        array_push($retval, ...$childs);
                    }
                }
            }
        }
        return $retval;
    }

    private function employeeAttendanceByParrent($parent_code, $startDate, $endDate)
    {
        $department_codes = $this->allChildDepartmentCode($parent_code);
        array_push($department_codes, $parent_code);
        return $this->employeeAttendanceByDeptCodes($department_codes, $startDate, $endDate);
    }

    private function employeeAttendanceByDeptCodes($departmentCodes, $startDate, $endDate)
    {
        $query = Attendance::query()
            ->join('role_employee as re', 're.employee_uuid', '=', 'attendances.employee_uuid')
            ->join('employees as e', 'e.uuid', '=', 'attendances.employee_uuid')
            ->join('roles as r', 're.role_id', '=', 'r.id')
            ->join('departments as d', 'd.code', '=', 'r.department_code')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('d.code', $departmentCodes)
            ->orderBy('attendances.date', 'asc')
            ->orderBy('attendances.time_in', 'asc')
            ->select('d.name as department', 'r.display_name as role', 'attendances.*', 'e.*');
        return $query->get();
    }

    public function exportNew2($date)
    {
        ob_end_clean(); // this
        ob_start(); // and this
        set_time_limit(0);
        $date = explode(' to ', urldecode($date));
        $startDate = strtotime($this->tanggal($date[0]));
        $endDate = strtotime($this->tanggal($date[1]));

        $divisions = [];
        $bod = Department::query()->where('code', '=', 'BOD')->first();
        $divisions[$bod->name] = ['BOD'];

        $departmens = Department::query()->where('parent_code', '=', 'BOD')->get();
        foreach ($departmens as $departmen) {
            $divisions[$departmen->name] = $this->allChildDepartmentCode($departmen->code);
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $styleHeader = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'font' => [
                'bold' => true
            ]
        ];

        // column header
        $spreadsheet = new Spreadsheet();
        $idxSheet = 1;

        foreach ($divisions as $name => $department_codes) {
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $name);
            $spreadsheet->addSheet($sheet, $idxSheet);
            $sheet = $spreadsheet->getSheet($idxSheet);
            $idxSheet++;
            $dates = [];
            $sheet->setCellValueByColumnAndRow(1, 1, 'ID')->mergeCells('A1:A2');
            $sheet->getColumnDimension('A')->setWidth(18);
            $sheet->getStyle('A1:A2')->getFont()->setName('Arial');
            $sheet->setCellValueByColumnAndRow(2, 1, 'Name')->mergeCells('B1:B2');
            $sheet->getColumnDimension('B')->setWidth(27);
            $sheet->setCellValueByColumnAndRow(3, 1, 'Role')->mergeCells('C1:C2');
            $sheet->getColumnDimension('C')->setWidth(21);
            $sheet->setCellValueByColumnAndRow(4, 1, 'Departement')->mergeCells('D1:D2');
            $sheet->getColumnDimension('D')->setWidth(29);
            $sheet->getStyle('A1:D2')->applyFromArray($styleHeader);
            $currentDate = $startDate;
            $i = 5;
            $columnIn = '';
            $columnOut = '';
            while ($currentDate <= $endDate) {
                $sheet->setCellValueByColumnAndRow($i, 1, date('Y-m-d', $currentDate));
                $columnIn = $sheet->getColumnDimensionByColumn($i)->getColumnIndex();
                $sheet->setCellValueByColumnAndRow($i, 2, 'IN')->getStyle($columnIn . '2')->getAlignment()->setHorizontal('center');
                $columnOut = $sheet->getColumnDimensionByColumn($i + 1)->getColumnIndex();
                $sheet->setCellValueByColumnAndRow($i + 1, 2, 'OUT')->getStyle($columnOut . '2')->getAlignment()->setHorizontal('center');
                $sheet->mergeCells($columnIn . '1:' . $columnOut . '1');
                $sheet->getStyle($columnIn . '1:' . $columnOut . '1')->applyFromArray($styleHeader);
                $dates[] = date('Y-m-d', $currentDate);
                $currentDate = strtotime("+1 day", $currentDate);
                $i = $i + 2;
            }
            $query = EmployeeModel::join('role_employee as re', 're.employee_uuid', '=', 'employees.uuid')
                ->whereNotIn('employees.uuid', ['F1F64912-6E8D-4784-9815-5CA71170329E', 'A738B637-D0A6-42BC-BA90-0F65FD3D9025', 'D3587F00-2422-4361-9141-D71D761039BD', '20663A1B-47C8-4C65-9FA5-A0FA95E3F4A8', '2D4BD3B6-CC5C-4765-8B1E-A2F5FFDD1E2F', '5208438F-6BA0-4903-BBFA-68BD2FF46447'])
                ->join('roles as r', 'r.id', '=', 're.role_id')
                ->join('departments as d', 'r.department_code', '=', 'd.code')
                ->whereIn('d.code', $department_codes)
                ->select('employees.uuid', 'employees.name', 'employees.empl_id', 'r.name as role', 'd.name as department')
                ->get();

            $currentDate = $startDate;
            $attendIn = '';
            $attendOut = '';
            $j = 0;
            $timeIn = '';
            $timeOut = '';
            foreach ($query as $key => $employee) {
                $i = 5;
                $j = $key + 3;
                $sheet->setCellValueByColumnAndRow(1, $j, $employee->empl_id)->getStyle('A3:A' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                $sheet->setCellValueByColumnAndRow(2, $j, $employee->name)->getStyle('B3:B' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                $sheet->setCellValueByColumnAndRow(3, $j, $employee->role)->getStyle('C3:C' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                $sheet->setCellValueByColumnAndRow(4, $j, $employee->department)->getStyle('D3:D' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                // $attendances = Attendance::where('attendances.employee_uuid', '=', 'E642DD65-1E77-40D9-9A83-06914D066BD9')
                $attendances = Attendance::where('attendances.employee_uuid', '=', $employee->uuid)
                    ->where('attendances.date', '>=', date('Y-m-d', $startDate))
                    ->where('attendances.date', '<=', date('Y-m-d', $endDate))
                    // ->leftJoin('mst_shift as d', 'attendances.shift_cd', '=', 'd.shift_cd')
                    // ->leftJoin('employee_shift as e', 'attendances.id', '=', 'e.attendance_id')
                    // , 'e.date_in as edate_in', 'd.sched_in', 'e.date_out as edate_out', 'd.sched_out'
                    ->get(['attendances.date', 'attendances.date_in', 'attendances.time_in', 'attendances.date_out', 'attendances.time_out', 'attendances.shift_cd', 'attendances.employee_uuid'])
                    ->keyBy('date');
                foreach ($dates as $date) {
                    $attendIn = $sheet->getColumnDimensionByColumn($i)->getColumnIndex();
                    $attendOut = $sheet->getColumnDimensionByColumn($i + 1)->getColumnIndex();

                    if (date("l", strtotime($date)) == 'Saturday' || date("l", strtotime($date)) == 'Sunday') {
                        $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
                        $sheet->getStyle($attendOut . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
                    }

                    if ($attendances->has($date)) {
                        $attendance = $attendances[$date];
                        $shift = EmployeeShift::join('mst_shift as ms', 'employee_shift.shift_cd', '=', 'ms.shift_cd')
                            ->where('employee_shift.date', '=', $date)
                            ->where('employee_shift.employee_uuid', '=', $attendance->employee_uuid)
                            ->select('employee_shift.date_in as edate_in', 'ms.sched_in', 'employee_shift.date_out as edate_out', 'ms.sched_out')
                            ->first();
                        // $schedIn = ($attendance->sched_in) ? strtotime($attendance->sched_in) : null;
                        // $schedOut = ($attendance->sched_out) ? strtotime($attendance->sched_out) : null;
                        // $eDateIn = strtotime($attendance->date);
                        // $eDateOut = strtotime($attendance->date);
                        if ($shift) {
                            $schedIn = ($shift->sched_in) ? ($shift->sched_in) : '08:00:00.0000000';
                            $schedOut = ($shift->sched_out) ? ($shift->sched_out) : '17:00:00.0000000';
                        } else {
                            $schedIn = '08:00:00.0000000';
                            $schedOut = '17:00:00.0000000';
                        }
                        $eDateIn = ($attendance->date);
                        $eDateOut = ($attendance->date);
                        $eSchedIn = $eDateIn . ' ' . $schedIn;
                        $eSchedOut = $eDateOut . ' ' . $schedOut;
                        $sheet->getStyle($attendIn)->getAlignment()->setVertical('center')->setHorizontal('center');
                        $sheet->setCellValueByColumnAndRow($i, $j, substr($attendance->time_in, 0, 5));
                        $sheet->getStyle($attendOut)->getAlignment()->setVertical('center')->setHorizontal('center');
                        $sheet->setCellValueByColumnAndRow($i + 1, $j, substr($attendance->time_out, 0, 5));
                        // $dateIn = ($attendance->date_in) ? strtotime($attendance->date_in) : strtotime($attendance->date);
                        $dateIn = ($attendance->date_in) ? ($attendance->date_in) : ($attendance->date);
                        $timeIn = ($attendance->time_in);
                        $timeOut = ($attendance->time_out);
                        // $timeIn = strtotime($attendance->time_in);
                        $aTimeIn = $dateIn . " " . $timeIn;
                        // $diffTimeIn = $timeIn - $schedIn; //pake strtotime
                        // $diffTimeIn = $aTimeIn - $eSchedIn;
                        // $diffJam    = floor($diffTimeIn / (60 * 60));
                        // $diffSisaMenit    = $diffTimeIn - $diffJam * (60 * 60); //sisa menit
                        // $diffJam    = floor($diffTimeIn / (60 * 60));
                        // $diffMenit    = floor($diffTimeIn / 60);
                        $time = new DateTime($eSchedIn);
                        $diff = $time->diff(new DateTime($aTimeIn));
                        $diffMenit = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                        // dd($schedIn, $schedOut, $eDateIn, $eDateOut, $eSchedIn, $eSchedOut, $aTimeIn, $diffMenit, $diff->format("%R"));
                        if (date("l", strtotime($date)) == 'Saturday' || date("l", strtotime($date)) == 'Sunday') {
                            if ($timeIn || $timeIn == '') {
                                $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                            }
                            if ($timeOut || $timeOut == '') {
                                $sheet->getStyle($attendOut . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                            }
                        }

                        if ($timeIn) {
                            if ($diff->format("%R") == '+' && $diffMenit > 10 && $schedIn) {
                                $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FBDE00');
                            }
                        } else {
                            $sheet->getStyle($attendIn . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');
                        }

                        if (!$timeOut) {
                            $sheet->getStyle($attendOut . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');
                        }
                    }
                    $i = $i + 2;
                }
            }
            if ($j > 0) {
                $sheet->getStyle('A1:' . $attendOut . $j)->applyFromArray($styleArray);
            }
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Employee Attendance.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
