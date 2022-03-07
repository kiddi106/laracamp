<?php

namespace App\Http\Controllers\Er;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\Department;
use App\Models\Er\Attendance;
use App\Models\Er\Company;
use App\Models\Er\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\Er\EmployeeShift;

use App\Models\Er\Employee as EmployeeModel;
use DateTime;
use League\CommonMark\Inline\Element\Code;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProjectReportController extends Controller
{

    function tanggalDb($date)
    {
        $exp = explode('/', $date);
        $date = $exp[2] . '-' . $exp[0] . '-' . $exp[1];
        return $date;
    }

    public function attendances()
    {
        $data['departments'] = Project::join('MbpsDb.dbo.employee_project as ep','ep.project_code','=','departments.code')
                                        ->where('ep.employee_uuid',Auth::user()->uuid)
                                        ->get();

        $data['companies'] = Company::all();
        return view('er.report.attendances',$data);
    }

    public function tanggal($date)
    {
        $exp = explode('-', $date);
        $date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
        return $date;
    }

    public function getDepartment(Request $request)
    {
        $company_id = $request->company;
        $department = Project::where('company_id', '=', $company_id)->get();
        return response()->json($department);
    }

    private function allChildDepartmentCode($parent_code)
    {
        $retval = [];
        if ($parent_code != null) {
            $departments = Project::query()->where('parent_code', '=', $parent_code)->get();
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

    public function exportAttendances($company, $department, $date)
    {
        ob_end_clean(); // this
        ob_start(); // and this
        set_time_limit(0);
        $date = explode(' to ', urldecode($date));
        $startDate = strtotime($this->tanggal($date[0]));
        $endDate = strtotime($this->tanggal($date[1]));

        $file_name = Company::findOrFail($company)->name;
        $projects = Project::query()->where('company_id', '=', $company)->get();
        if ($department != "all") {
            $projects = Project::query()->where('company_id', '=', $company)->where('code', '=', $department)->get();
            $file_name = Project::where('code', '=', $department)->first()->name;
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

        $styleTitle = [
            'alignment' => [
                'horizontal' => 'left',
                'vertical' => 'center',
            ],
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ];

        // column header
        $spreadsheet = new Spreadsheet();
        $idxSheet = 0;

        foreach ($projects as $project) {
            $nameSheet = $project->company->name.'-'.($idxSheet+1);
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nameSheet);
            $spreadsheet->addSheet($sheet, $idxSheet);
            $sheet = $spreadsheet->getSheet($idxSheet);
            $idxSheet++;
            $dates = [];
            $sheet->setCellValueByColumnAndRow(1, 1, $project->name)->mergeCells('A1:D1');
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getStyle('A1:D1')->applyFromArray($styleTitle);
            $sheet->setCellValueByColumnAndRow(1, 2, 'ID')->mergeCells('A2:A3');
            $sheet->getColumnDimension('A')->setWidth(18);
            $sheet->getStyle('A1:A2')->getFont()->setName('Arial');
            $sheet->setCellValueByColumnAndRow(2, 2, 'Name')->mergeCells('B2:B3');
            $sheet->getColumnDimension('B')->setWidth(27);
            $sheet->setCellValueByColumnAndRow(3, 2, 'Project')->mergeCells('C2:C3');
            $sheet->getColumnDimension('C')->setWidth(29);
            $sheet->setCellValueByColumnAndRow(4, 2, 'Position')->mergeCells('D2:D3');
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getStyle('A2:D3')->applyFromArray($styleHeader);
            $currentDate = $startDate;
            $i = 5;
            $columnIn = '';
            $columnOut = '';
            while ($currentDate <= $endDate) {
                $sheet->setCellValueByColumnAndRow($i, 2, date('Y-m-d', $currentDate));
                $columnIn = $sheet->getColumnDimensionByColumn($i)->getColumnIndex();
                $sheet->setCellValueByColumnAndRow($i, 3, 'IN')->getStyle($columnIn . '2')->getAlignment()->setHorizontal('center');
                $columnOut = $sheet->getColumnDimensionByColumn($i + 1)->getColumnIndex();
                $sheet->setCellValueByColumnAndRow($i + 1, 3, 'OUT')->getStyle($columnOut . '2')->getAlignment()->setHorizontal('center');
                $sheet->mergeCells($columnIn . '1:' . $columnOut . '1');
                $sheet->getStyle($columnIn . '1:' . $columnOut . '1')->applyFromArray($styleHeader);
                $dates[] = date('Y-m-d', $currentDate);
                $currentDate = strtotime("+1 day", $currentDate);
                $i = $i + 2;
            }
            $query = EmployeeModel::join('role_employee as re', 're.employee_uuid', '=', 'employees.uuid')
                ->join('roles as r', 'r.id', '=', 're.role_id')
                ->join('departments as d', 'r.department_code', '=', 'd.code')
                ->where('d.code', $project->code)
                ->select('employees.uuid', 'employees.name', 'employees.empl_id', 'r.display_name as role', 'd.name as department')
                ->get();

            $currentDate = $startDate;
            $attendIn = '';
            $attendOut = '';
            $j = 0;
            $timeIn = '';
            $timeOut = '';
            foreach ($query as $key => $employee) {
                $i = 5;
                $j = $key + 4;
                $sheet->setCellValueByColumnAndRow(1, $j, $employee->empl_id)->getStyle('A3:A' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                $sheet->setCellValueByColumnAndRow(2, $j, $employee->name)->getStyle('B3:B' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                $sheet->setCellValueByColumnAndRow(3, $j, $employee->department)->getStyle('C3:C' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                $sheet->setCellValueByColumnAndRow(4, $j, $employee->role)->getStyle('D3:D' . $j)->getAlignment()->setVertical('top')->setHorizontal('left')->setWrapText(true);
                $attendances = Attendance::where('attendances.employee_uuid', '=', $employee->uuid)
                    ->where('attendances.date', '>=', date('Y-m-d', $startDate))
                    ->where('attendances.date', '<=', date('Y-m-d', $endDate))
                    ->get(['attendances.date', 'attendances.date_in', 'attendances.time_in', 'attendances.date_out', 'attendances.time_out', 'attendances.shift_id', 'attendances.employee_uuid'])
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
                        $shift = EmployeeShift::join('mst_shift as ms', 'employee_shift.shift_id', '=', 'ms.id')
                            ->where('employee_shift.date', '=', $date)
                            ->where('employee_shift.employee_uuid', '=', $attendance->employee_uuid)
                            ->select('employee_shift.date_in as edate_in', 'ms.sched_in', 'employee_shift.date_out as edate_out', 'ms.sched_out')
                            ->first();
                        // $schedIn = ($attendance->sched_in) ? strtotime($attendance->sched_in) : null;
                        // $schedOut = ($attendance->sched_out) ? strtotime($attendance->sched_out) : null;
                        // $eDateIn = strtotime($attendance->date);
                        // $eDateOut = strtotime($attendance->date);
                        if ($shift) {
                            $schedIn = ($shift->sched_in) ? ($shift->sched_in) : '';
                            $schedOut = ($shift->sched_out) ? ($shift->sched_out) : '';
                        } else {
                            $schedIn = '';
                            $schedOut = '';
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
                $sheet->getStyle('A2:' . $attendOut . $j)->applyFromArray($styleArray);
            }
        }
        $nameFile = 'Employee Attendance'.'-'.$file_name;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nameFile.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
