<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Er\Attendance;
use App\Models\Er\Company;
use App\Models\Er\Employee;
use App\Models\Er\EmployeeProject;
use App\Models\Er\Location;
use App\Models\Er\Project;
use App\Models\Mst\DepartmentGroupDetail;
use App\Models\Payroll\PayrollEmployee;
use App\Models\Payroll\PayrollEmployeeVariables;
use App\Models\Payroll\ProjectPayroll;
use App\Models\Payroll\ProjectPayrollVariables;
use App\Models\Payroll\Ptkp;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use function GuzzleHttp\Promise\all;

class ProjectPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $query = ProjectPayroll::orderByDesc('created_at')->get();
        // dd($query['1']->project->name);
        return view('payroll.project.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['companies'] = Company::all();

        return view('payroll.project.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cutoff_salary = explode(' to ',$request->cut_off_salary);
        $exp_salary_start = explode('-',$cutoff_salary[0]);
        $salary_start = $exp_salary_start[2].'-'.$exp_salary_start[1].'-'.$exp_salary_start[0];
        $exp_salary_end = explode('-',$cutoff_salary[1]);
        $salary_end = $exp_salary_end[2].'-'.$exp_salary_end[1].'-'.$exp_salary_end[0];

        $cutoff_allowance = explode(' to ',$request->cut_off_allowance);
        $exp_allowance_start = explode('-',$cutoff_allowance[0]);
        $allowance_start = $exp_allowance_start[2].'-'.$exp_allowance_start[1].'-'.$exp_allowance_start[0];
        $exp_allowance_end = explode('-',$cutoff_allowance[1]);
        $allowance_end = $exp_allowance_end[2].'-'.$exp_allowance_end[1].'-'.$exp_allowance_end[0];

        $exp_payment_date = explode('-',$request->payment_date);
        $payment_date = $exp_payment_date[2].'-'.$exp_payment_date[1].'-'.$exp_payment_date[0];

        $model = new ProjectPayroll();
        $model->project_code = $request->project;
        $model->month = $request->month;
        $model->year = $request->year;

        $model->cutoff_salary_start = $salary_start;
        $model->cutoff_salary_end = $salary_end;
        $model->cutoff_allowance_start = $allowance_start;
        $model->cutoff_allowance_end = $allowance_end;
        $model->payment_date = $payment_date;

        
        $model->created_by = Auth::user()->uuid;
        $model->status_id = 1;


        if ($model->save()) 
        {
            foreach ($request->variables as $key => $value) {
                $variable = new ProjectPayrollVariables();
                $variable->project_payroll_id = $model->id;
                $variable->variable_id = $value;
                $variable->save();
            }
            $res['res'] = 'success';
            $res['id'] = base64_encode($model->id);
        } else {
            $res['res'] = 'failed';
        }
        return $res;


    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = base64_decode($id);
        $data['model'] = ProjectPayroll::find($id);
        $variables = ProjectPayrollVariables::where('project_payroll_id',$data['model']->id)->get();
        $ptkps = Ptkp::get();

        // $projects = DepartmentGroupDetail::where('group_id',$data['model']->project_code)->get();

        // foreach ($projects as $key => $value) {
        //     $project[] = $value['department_code'] ;
        // }
        // $query = Employee::whereIn('department_code',$project)->get();

        // dd($query);

        $data['countEmp'] = Employee::where('department_code',$data['model']->project_code)->count();
        $data['countEmpPay'] = PayrollEmployee::where('project_payroll_id',$id)->count();

        $data['allowance'] = [];
        $data['deduction'] = [];
        $variable[] = [];
        
        foreach ($ptkps as $key => $value) {
            $ptkp[] = $value->name;
        }


        foreach ($variables as $key => $value) {
            $variable[] = $value->variable->name;
            switch ($value->variable->type) {
                case 1:
                    $data['allowance'][] = array(
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                        'counter' => $value->variable->counter,
                    );
                break;
                case 2:
                    $data['deduction'][] = array(
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                        'counter' => $value->variable->counter,
                    );
                break;
                case 3:
                    $data['allowance'][] = array(
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                        'counter' => $value->variable->counter,
                    );
                break;
                case 4:
                    $data['deduction'][] = array(
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                        'counter' => $value->variable->counter,
                    );
                break;
                default:
                    # code...
                break;
            }
        }
        $data['countA'] = count($data['allowance']);
        $data['countD'] = count($data['deduction']);
        $data['variable'] = json_encode($variable);
        $data['ptkp'] = json_encode($ptkp);



        return view('payroll.project.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $data['model'] = ProjectPayroll::find($id);
        $variables = ProjectPayrollVariables::where('project_payroll_id',$data['model']->id)->get();
        $ptkps = Ptkp::get();

        $data['countEmp'] = Employee::where('department_code',$data['model']->project_code)->count();
        $data['countEmpPay'] = PayrollEmployee::where('project_payroll_id',$id)->count();
        $data['allowance'] = [];
        $data['deduction'] = [];
        $variable[] = [];

        foreach ($ptkps as $key => $value) {
            $ptkp[] = $value->name;
        }

        foreach ($variables as $key => $value) {
            $variable[] = $value->variable->name;
            switch ($value->variable->type) {
                case 1:
                    $data['allowance'][] = array(
                        'id' => $value->id,
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                    );
                break;
                case 2:
                    $data['deduction'][] = array(
                        'id' => $value->id,
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                    );
                break;
                default:
                    # code...
                break;
            }
        }
        $data['countA'] = count($data['allowance']);
        $data['countD'] = count($data['deduction']);
        $data['variable'] = json_encode($variable);
        $data['ptkp'] = json_encode($ptkp);



        return view('payroll.project.edit',$data);
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

    public function updateDate(Request $request)
    {
        $id = base64_decode($request->id);
        $cutoff_salary = explode(' to ',$request->cut_off_salary);
        $exp_salary_start = explode('-',$cutoff_salary[0]);
        $salary_start = $exp_salary_start[2].'-'.$exp_salary_start[1].'-'.$exp_salary_start[0];
        $exp_salary_end = explode('-',$cutoff_salary[1]);
        $salary_end = $exp_salary_end[2].'-'.$exp_salary_end[1].'-'.$exp_salary_end[0];

        $cutoff_allowance = explode(' to ',$request->cut_off_allowance);
        $exp_allowance_start = explode('-',$cutoff_allowance[0]);
        $allowance_start = $exp_allowance_start[2].'-'.$exp_allowance_start[1].'-'.$exp_allowance_start[0];
        $exp_allowance_end = explode('-',$cutoff_allowance[1]);
        $allowance_end = $exp_allowance_end[2].'-'.$exp_allowance_end[1].'-'.$exp_allowance_end[0];

        $exp_payment_date = explode('-',$request->payment_date);
        $payment_date = $exp_payment_date[2].'-'.$exp_payment_date[1].'-'.$exp_payment_date[0];

        $model = ProjectPayroll::find($id);

        $model->cutoff_salary_start = $salary_start;
        $model->cutoff_salary_end = $salary_end;
        $model->cutoff_allowance_start = $allowance_start;
        $model->cutoff_allowance_end = $allowance_end;
        $model->payment_date = $payment_date;
        
        $model->updated_by = Auth::user()->uuid;
        $model->updated_at = now();
        $model->save();

        return redirect()->route('payroll.project.edit',['id' => base64_encode($id)])->with('alert.success', 'Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyVariable($id)
    {
        $id = base64_decode($id);
        $model = ProjectPayrollVariables::find($id);
        $model->delete();
    }

    public function destroy($id)
    {
        $id = base64_decode($id);

        $model = ProjectPayroll::find($id);
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->deleted_by = Auth::user()->uuid;
        $model->save();
    }

    public function datatables(Request $request)
    {

        $query = ProjectPayroll::orderBy('created_at', 'asc')->get();
        
        return DataTables::of($query)
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
                $string .= '<a href="'.route('payroll.project.show',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary" title="Show"><i class="fa fa-eye"></i></a>';
                if ($model->status_id == 1) {
                    $string .= '<a href="'.route('payroll.project.edit',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-secondary" title="Edit"><i class="fa fa-edit"></i></a>';                    # code...
                    $string .= '<a href="'.route('payroll.project.destroy',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';                    # code...
                }
                $string .= '</div>';
            return
                $string;
        })
        ->addColumn('project', function ($model) {
            $string = $model->group->name;
            return $string;
        })
        ->addColumn('status', function ($model) {

            $string = '<span class="right badge badge-'.$model->status->class.' ">'.$model->status->name.'</span>';
            return $string;
        })
        ->editColumn('month', function ($model) {
            
            $dateObj   = DateTime::createFromFormat('!m', $model->month);
            $monthName = $dateObj->format('F'); // March

            return $monthName;
        })
        ->addColumn('checkbox', function ($model) {
            if ($model->status_id == 2) {
                return '<input type="checkbox" name="projectPayrolId[]" id="_projectPayrolId' . $model->id . '" class="checked-select" value="' . $model->id . '">';
            }
            return '';
        })
        ->addIndexColumn()
        ->rawColumns(['action','project','status','checkbox'])
        ->make(true);
    }

    public function datatablesEmp(Request $request)
    {
        $projects = DepartmentGroupDetail::where('group_id',$request->project_code)->get();

        foreach ($projects as $key => $value) {
            $project[] = $value['department_code'] ;
        }
        $query = Employee::whereIn('department_code',$project)->get();
         
        return DataTables::of($query)
        ->addColumn('action', function ($model) use ($request) {

            $cek = PayrollEmployee::where('project_payroll_id',$request->project_payroll_id)->where('employee_uuid',$model->uuid)->get();

            $string = '<div class="btn-group">';
                $string .= '<a href="'.route('er.jo.employeement.edit',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-info modal-show edit" title="Edit Employee"><i class="fa fa-edit"></i></a>';
                if (count($cek) > 0) {
                    $string .= '&nbsp;&nbsp;<a href="'.route('payroll.project.payroll',['id' => base64_encode($cek[0]->id)]) .'" target="_blank" type="button" class="btn btn-xs btn-success" title="Show Payroll"><i class="fa fa-eye"></i></a>';
                    $string .= '&nbsp;&nbsp;<a href="'.route('payroll.project.payslip',['id' => base64_encode($cek[0]->id)]) .'" target="_blank" type="button" class="btn btn-xs btn-info" title="Show Payslip"><i class="fa fa-eye"></i></a>';
                    $string .= '&nbsp;&nbsp;<a href="'.route('payroll.project.spt',['id' => base64_encode($cek[0]->id)]) .'" target="_blank" type="button" class="btn btn-xs btn-default" title="Download SPT"><i class="fa fa-file"></i></a>';
                    $string .= '&nbsp;&nbsp;<a href="'.route('payroll.project.destroyPayroll',['id' => base64_encode($cek[0]->id)]) .'" target="_blank" type="button" class="btn btn-xs btn-danger btn-delete" title="Delete Payroll"><i class="fa fa-times"></i></a>';
                }
                $string .= '</div>';
            return
                $string;
        })
        ->addColumn('check', function ($model)  use($request) {

            $cek = PayrollEmployee::where('project_payroll_id',$request->project_payroll_id)->where('employee_uuid',$model->uuid)->count();

            if ($cek > 0) {
                $string = '<i class="fas fa-check"></i>';
            } else {
                $string = '';
            }
            return
                $string;
        })
        
        ->addIndexColumn()
        ->rawColumns(['action','check'])
        ->make(true);
    }

    public function storeGrid(Request $request)
    {
        $data = json_decode($request->tableData);
        $projectPayrollId = $request->projectPayrollId;
        $days = date('z', strtotime(date('y').'-12-31'))+1;

        


        foreach ($data as $key => $cell) {


            if ($cell[0] != null) {
                $check = Employee::where('empl_id',$cell[0])->get();
                $projectPayroll = ProjectPayroll::find($projectPayrollId);
                $countAttendance = Attendance::whereBetween('date', [$projectPayroll->cutoff_salary_start, $projectPayroll->cutoff_salary_end])->where('employee_uuid',$check[0]->uuid)->count();
                $countAllowance = Attendance::whereBetween('date', [$projectPayroll->cutoff_allowance_start, $projectPayroll->cutoff_allowance_end])->where('employee_uuid',$check[0]->uuid)->count();

                if (count($check) > 0) {

                    $uuid = $check[0]->uuid;
                    $allowance = 0;
                    $deduction = 0;

                    $variables = ProjectPayrollVariables::where('project_payroll_id',$projectPayrollId)->get();
                    $index = 5;
                    foreach ($variables as $key => $item) {
                        $variable[] = $item->variable->name;
                        $value[] = $cell[$index];

                        $gaji = $cell[4];
                        $pengali = $cell[3];
                        $penghitung = 0;

                        if ($pengali != '' || $pengali > 0) {
                            $penghitung = $pengali;
                        } else {
                            $penghitung = $gaji;
                        }


                        if ($item->variable->tax_counter == 1) {
                            switch ($item->variable->type) {
                            
                                case 1:
                                    if ($item->variable->model == 2) {
                                        if ($item->variable->group) {
                                            if ($penghitung > $item->variable->group->max ) {
                                                $nilai = ($item->variable->group->max * ($item->variable->percentage/100));
                                            } else {
                                                $nilai = ($penghitung * ($item->variable->percentage/100));
                                            }
                                        } else {
                                                $nilai = ($penghitung * ($item->variable->percentage/100));
                                        }

                                    } else {
                                        if ($item->variable->counter == 1) {
                                            $nilai = $cell[$index] * $days; //per days
                                        } else {
                                            $nilai = $cell[$index]; //per months
                                        }
                                    }
                                    
                                    $allowance = $allowance + $nilai;
                                break;
                                case 2:
                                    if ($item->variable->model == 2) {
                                        if ($item->variable->group) {
                                            if ($penghitung > $item->variable->group->max ) {
                                                $nilai = ($item->variable->group->max * ($item->variable->percentage/100));
                                            } else {
                                                $nilai = ($penghitung * ($item->variable->percentage/100));
                                            }
                                        } else {
                                                $nilai = ($penghitung * ($item->variable->percentage/100));
                                        }
                                    } else {
                                        if ($item->variable->counter == 1) {
                                            $nilai = $cell[$index] * $days; //per days
                                        } else {
                                            $nilai = $cell[$index]; //per months
                                        }
                                    }
                                    
                                    $deduction = $deduction + $nilai;
                                break;
                                default:
                                    # code...
                                break;
                            }
                        }
                        $index++;
                    }

                    $pajaka = 0;
                    $a = 0;

                    $rumus = $this->rumus($projectPayroll->project_code,$projectPayroll->year,$gaji,$uuid);

                    $avg = $this->avg($projectPayroll->project_code,$projectPayroll->year,$allowance,$deduction,$uuid);

                    if ($check[0]->resign_date != null) {
                        $avgBasic = $rumus['resign'];
                    } else {
                        $avgBasic = $rumus['average'];
                    }
                    // 1 : nett; 2 : gross

                    $type = ($cell[2] == 'Net') ? 1 : 2 ;

                    if ($type == 2) { // gross
                        $bruto = ($avgBasic + $pajaka + $avg['allowance']) * 12;
    
                            // $bruto = $basic;
                            $biayaJabatan = $bruto * (5/100);
                            
                            if ($biayaJabatan > 6000000) {
                                $biayaJabatan = 6000000;
                            }
                            $deductionSetahun = $avg['deduction'] * 12;
                            $netto = $bruto - ($biayaJabatan + $deductionSetahun); 
        
                            $ptkp = Ptkp::where('name',$cell[1])->get();
        
                            $pkp = $netto - $ptkp[0]->value;
    
                            if (substr($pkp,-3)<1000){
                                $itung = $pkp/1000;
                                $pkp=floor($itung).'000';
                            } else {
                                $pkp=round($pkp,-3);
                            } 
                            
        
                            if ($pkp < 0) {
                                $pkp = $pkp;
                                $pajak = 0;
                                $pajakPerbulan = 0;
                            } else {
                                $batas = $this->batasPenghasilan($pkp);
                                $pajak = round($batas,0);
                                $pajakPerbulan =  round($pajak/12,0);
                            }
                            // dd($pkp);
                    } else { // nett
                        do {
                            $pajaka = $a;
                            $bruto = ($avgBasic + $pajaka + $avg['allowance']) * 12;
    
                            // $bruto = $basic;
                            $biayaJabatan = $bruto * (5/100);
                            
                            if ($biayaJabatan > 6000000) {
                                $biayaJabatan = 6000000;
                            }
                            $deductionSetahun = $avg['deduction'] * 12;
                            $netto = $bruto - ($biayaJabatan + $deductionSetahun); 
        
                            $ptkp = Ptkp::where('name',$cell[1])->get();
        
                            $pkp = $netto - $ptkp[0]->value;
    
                            if (substr($pkp,-3)<1000){
                                $itung = $pkp/1000;
                                $pkp=floor($itung).'000';
                            } else {
                                $pkp=round($pkp,-3);
                            } 
                            
        
                            if ($pkp < 0) {
                                $pkp = $pkp;
                                $pajak = 0;
                                $pajakPerbulan = 0;
                            } else {
                                $pkp = $this->batasPenghasilan($pkp);
                                $pajak = round($pkp,0);
                                $pajakPerbulan =  round($pajak/12,0);
                            }
                            $a = $pajakPerbulan;


                        } while ($pajaka < $pajakPerbulan);
                    }
                    
                    // dd($pajak);

                    $irregular = $this->irregular($cell,$projectPayrollId,$days);
                    

                    $php21AtasIr = $irregular - $pajak;

                    if ($check[0]->resign_date != null) {
                        $bulan = 12;
                    } else {
                        $bulan = $rumus['bulanPajak'];
                    }

                    $pph21RegIr = $php21AtasIr +  ($pajakPerbulan * $bulan);

                    $pph21 = $pph21RegIr - $rumus['totalPajak'];

                    // echo $php21AtasIr.'<br>';
                    // echo $pajakPerbulan * $bulan.'<br>';
                    // echo $pph21RegIr.'<br>';
                    // echo $rumus['totalPajak'].'<br>';
                    // echo $pph21.'<br>';

                    // dd($avgBasic);

                    //check existing or not
                    $emp = PayrollEmployee::where('employee_uuid',$check[0]->uuid)->where('project_payroll_id',$projectPayrollId)->get();

                    if (count($emp) > 0) {
        
                    }
                    else {
                        $payroll = new PayrollEmployee();
                        $payroll->employee_uuid = $uuid;
                        $payroll->project_payroll_id = $projectPayrollId;
                        $payroll->ptkp_id = $ptkp[0]->id;
                        $payroll->basic_salary = $gaji;
                        $payroll->bruto = $bruto;
                        $payroll->netto = $netto;
                        $payroll->pkp = $pajak;
                        $payroll->pajak = $pph21;
                        $payroll->pengali = $pengali;
                        $payroll->biaya_jabatan = $biayaJabatan;
                        $payroll->count_attendance = $countAttendance;
                        $payroll->count_allowance = $countAllowance;
                        if ($check[0]->npwp == '' || $check[0]->npwp == null) {
                            $payroll->tax_penalty = $pph21*(20/100);
                        }
                        if ($type == 2) {
                            $payroll->type = 'Gross';
                            $payroll->tax_allowance = 0;
                        } else {
                            $payroll->type = 'Net';
                            $payroll->tax_allowance = $pph21;
                        }
                        //$payroll->save();
                        if ($payroll->save()) {
                            $index = 5;
                            foreach ($variables as $key => $item) {
                                if ($item->variable->model == 2) {
                                    if ($item->variable->group) {
                                        if ($gaji < $item->variable->group->min ) {
                                            $nilai = ($item->variable->group->min * ($item->variable->percentage/100));
                                        } else if ($gaji > $item->variable->group->max ) {
                                            $nilai = ($item->variable->group->max * ($item->variable->percentage/100));
                                        } else {
                                            $nilai = ($gaji * ($item->variable->percentage/100));
                                        }
                                    } else {
                                        $nilai = ($gaji * ($item->variable->percentage/100));
                                    }
                                } else {
                                    $nilai = $cell[$index];
                                }

                                $payrollVariable = new PayrollEmployeeVariables();
                                $payrollVariable->payroll_employee_id = $payroll->id;
                                $payrollVariable->variable_id = $item->variable_id;
                                $payrollVariable->value = $nilai;
                                $payrollVariable->save();
    
                                $index++;
                            }
                        }
                    } // check empl payroll
                } // check empl_id
                else {
                    $res['res'] = 'falseId';
                    $res['data'] = $key+1;
                    return $res;
                }
            }

        } //end foreach datagrid

        
        $project = ProjectPayroll::find($projectPayrollId);
        $project->status_id = 2;

        if ($project->save()) {
            $res['res'] = 'success';
        } else {
            $res['res'] = 'failed';
        }

        return $res;

    }

    private function irregular($cell,$projectPayrollId,$days)
    {
        // dd($cell);
        if ($cell[0] != null) {
            $check = Employee::where('empl_id',$cell[0])->get();
            $projectPayroll = ProjectPayroll::find($projectPayrollId);
            $countAttendance = Attendance::whereBetween('date', [$projectPayroll->cutoff_salary_start, $projectPayroll->cutoff_salary_end])->where('employee_uuid',$check[0]->uuid)->count();
            $countAllowance = Attendance::whereBetween('date', [$projectPayroll->cutoff_allowance_start, $projectPayroll->cutoff_allowance_end])->where('employee_uuid',$check[0]->uuid)->count();

            if (count($check) > 0) {

                $uuid = $check[0]->uuid;
                $allowance = 0;
                $allowanceIr = 0;
                $deduction = 0;

                $variables = ProjectPayrollVariables::where('project_payroll_id',$projectPayrollId)->get();
                $index = 5;

                $gaji = $cell[4];
                $pengali = $cell[3];
                $penghitung = 0;

                if ($pengali != '' || $pengali > 0) {
                    $penghitung = $pengali;
                } else {
                    $penghitung = $gaji;
                }
                
                foreach ($variables as $key => $item) {
                    $variable[] = $item->variable->name;
                    $value[] = $cell[$index];

                    if ($item->variable->tax_counter == 1) {
                        switch ($item->variable->type) {
                        
                            case 1:
                                if ($item->variable->model == 2) {
                                    if ($item->variable->group) {
                                        if ($penghitung > $item->variable->group->max ) {
                                            $nilai = ($item->variable->group->max * ($item->variable->percentage/100));
                                        } else {
                                            $nilai = ($penghitung * ($item->variable->percentage/100));
                                        }
                                    } else {
                                        $nilai = ($penghitung * ($item->variable->percentage/100));
                                    }
                                } else {
                                    if ($item->variable->counter == 1) {
                                        $nilai = $cell[$index] * $days; //per days
                                    } else {
                                        $nilai = $cell[$index]; //per months
                                    }
                                }
                                $allowance = $allowance + $nilai;
                            break;
                            case 2:
                                if ($item->variable->model == 2) {
                                    if ($item->variable->group) {
                                        if ($penghitung > $item->variable->group->max ) {
                                            $nilai = ($item->variable->group->max * ($item->variable->percentage/100));
                                        } else {
                                            $nilai = ($penghitung * ($item->variable->percentage/100));
                                        }
                                    } else {
                                        $nilai = ($penghitung * ($item->variable->percentage/100));
                                    }
                                } else {
                                    if ($item->variable->counter == 1) {
                                        $nilai = $cell[$index] * $days; //per days
                                    } else {
                                        $nilai = $cell[$index]; //per months
                                    }
                                }

                                $deduction = $deduction + $nilai;
                            break;
                            case 3:
                                if ($item->variable->model == 2) {
                                    if ($item->variable->group) {
                                        if ($penghitung > $item->variable->group->max ) {
                                            $nilai = ($item->variable->group->max * ($item->variable->percentage/100));
                                        } else {
                                            $nilai = ($penghitung * ($item->variable->percentage/100));
                                        }
                                    } else {
                                        $nilai = ($penghitung * ($item->variable->percentage/100));
                                    }
                                } else {
                                    if ($item->variable->counter == 1) {
                                        $nilai = $cell[$index] * $days; //per days
                                    } else {
                                        $nilai = $cell[$index]; //per months
                                    }
                                }
                                $allowanceIr = $allowanceIr + $nilai;
                            break;
                            default:
                                # code...
                            break;
                        }
                    }
                    $index++;
                }
                $pajaka = 0;
                $a = 0;

                $avgAllIr = $this->allowanceIr($projectPayroll->project_code,$projectPayroll->year,$allowanceIr,$uuid);

                $rumus = $this->rumus($projectPayroll->project_code,$projectPayroll->year,$gaji,$uuid);

                $avg = $this->avg($projectPayroll->project_code,$projectPayroll->year,$allowance,$deduction,$uuid);

                // dd($avgAllIr);
                if ($check[0]->resign_date != null) {
                    $avgBasic = $rumus['resign'];
                } else {
                    $avgBasic = $rumus['average'];
                }

                $type = ($cell[2] == 'Net') ? 1 : 2 ;

                if ($type == 2) { // gross
                    $bruto = ($avgBasic + $pajaka + $avg['allowance']) * 12;
                    $bruto = $bruto + $avgAllIr;

                        // $bruto = $basic;
                        $biayaJabatan = $bruto * (5/100);
                        
                        if ($biayaJabatan > 6000000) {
                            $biayaJabatan = 6000000;
                        }
                        $deductionSetahun = $avg['deduction'] * 12;
                        $netto = $bruto - ($biayaJabatan + $deductionSetahun); 
    
                        $ptkp = Ptkp::where('name',$cell[1])->get();
    
                        $pkp = $netto - $ptkp[0]->value;

                        if (substr($pkp,-3)<1000){
                            $itung = $pkp/1000;
                            $pkp=floor($itung).'000';
                        } else {
                            $pkp=round($pkp,-3);
                        } 
                        
    
                        if ($pkp < 0) {
                            $pkp = 0;
                            $pajak = 0;
                            $pajakPerbulan = 0;
                        } else {
                            $batas = $this->batasPenghasilan($pkp);
                            $pajak = round($batas,0);
                            $pajakPerbulan =  round($pajak/12,0);
                        }
                } else { // nett
                    do {
                        $pajaka = $a;
                        $bruto = ($avgBasic + $pajaka + $avg['allowance']) * 12;
                        $bruto = $bruto + $avgAllIr;

                        // $bruto = $basic;
                        $biayaJabatan = $bruto * (5/100);
                        
                        if ($biayaJabatan > 6000000) {
                            $biayaJabatan = 6000000;
                        }
                        $deductionSetahun = $avg['deduction'] * 12;
                        $netto = $bruto - ($biayaJabatan + $deductionSetahun); 
    
                        $ptkp = Ptkp::where('name',$cell[1])->get();
    
                        $pkp = $netto - $ptkp[0]->value;

                        if (substr($pkp,-3)<1000){
                            $itung = $pkp/1000;
                            $pkp=floor($itung).'000';
                        } else {
                            $pkp=round($pkp,-3);
                        } 
                        
    
                        if ($pkp < 0) {
                            $pkp = 0;
                            $pajak = 0;
                            $pajakPerbulan = 0;
                        } else {
                            $batas = $this->batasPenghasilan($pkp);
                            $pajak = round($batas,0);
                            $pajakPerbulan =  round($pajak/12,0);
                        }
                        $a = $pajakPerbulan;

                        
                    } while ($pajaka < $pajakPerbulan);
                }

                $pph21 = ($pajakPerbulan * $rumus['bulanPajak']) - $rumus['totalPajak'];


                return $pajak;
                // dd(round($pkp,-3)) ;
            } // check empl_id
            else {
                return 0; 
            }
        }
    }

    public function batasPenghasilan($value)
    {
        $total = 0;

        if ( $value >= 1 && $value <= 50000000) {
            $sub = $value * (5/100);
            $total = $sub;
        } else if (50000001 <= $value && $value <= 250000000) {
            $sub1 = 50000000 * (5/100);
            $sisa = $value - 50000000;
            $sub2 = $sisa * (15/100);

            $total = $sub1 + $sub2;

        } else if (250000001 <= $value ||$value <= 500000000) {
            $sub1 = 50000000 * (5/100);
            $sub2 = 250000000 * (15/100);
            $sisa = 250000000 - $value;
            $sub3 = $sisa * (25/100);

            $total = $sub1 + $sub2 + $sub3;
        } else if ($value > 500000000) {
            $sub1 = 50000000 * (5/100);
            $sub2 = 250000000 * (15/100);
            $sub3 = 500000000 * (25/100);
            $sisa = 500000000 - $value;
            $sub4 = $sisa * (30/100);

            $total = $sub1 + $sub2 + $sub3 + $sub4;
        }
        return $total;

    }

    private function rumus($projectCode,$year,$salary,$uuid)
    {
        $project = ProjectPayroll::where('year',$year)->where('project_code',$projectCode)->get();

        foreach ($project as $key => $value) {

            $emp = PayrollEmployee::where('project_payroll_id',$value->id)->where('employee_uuid',$uuid)->get();
            if (count($emp)) {
                $basic[] = $emp[0]->basic_salary;
                $pajakTerbayar[] = $emp[0]->pajak;
            }
        }

        $basic[] = $salary;
        $pajakTerbayar[] = 0;
        $average = array_sum($basic)/count($basic);
        $totalPajak = array_sum($pajakTerbayar);
        $res['bulanPajak'] = count($pajakTerbayar);
        $res['average'] = round($average,0);
        $res['totalPajak'] = round($totalPajak,0);

        $resign = array_sum($basic)/12;
        $res['resign'] = round($resign,0);

        return $res;
    }

    private function allowanceIr($projectCode,$year,$allowances,$uuid)
    {
        $project = ProjectPayroll::where('year',$year)->where('project_code',$projectCode)->get();

        foreach ($project as $key => $value) {
            $emp = PayrollEmployee::where('project_payroll_id',$value->id)->where('employee_uuid',$uuid)->first();

            if ($emp) {
                // print_r(isset($value->payroll->variables[$key]));
                if (isset($emp->variables)) {
                    foreach ($emp->variables as $keys => $item) {
                        if ($item->variable->type == 3) {
                            $allowanceIr[] = $item->value;
                        }
                    }
                }
                $bulan[] = $value->payroll->id;
            }
        }

        $allowanceIr[] = $allowances;

        $bulan[] = 0;
        // $pajakTerbayar[] = 0;
        $res = array_sum($allowanceIr);

        // dd($allowanceIr);

        return $res;
    }

    private function avg($projectCode,$year,$allowance,$deduction,$uuid)
    {
        $project = ProjectPayroll::where('year',$year)->where('project_code',$projectCode)->get();

        foreach ($project as $key => $value) {
            $emp = PayrollEmployee::where('project_payroll_id',$value->id)->where('employee_uuid',$uuid)->first();
            if ($emp) {
                if (isset($emp->variables)) {
                    foreach ($emp->variables as $keys => $item) {
                        if ($item->variable->type == 1) {
                            $allowances[] = $item->value;
                        } else if($item->variable->type == 2) {
                            $deductions[] = $item->value;
                        }                        
                    }
                }
                $bulan[] = $value->payroll->id;
            }
        }



        $allowances[] = $allowance;
        $deductions[] = $deduction;


        $bulan[] = 0;
        // $pajakTerbayar[] = 0;

        $check = Employee::where('uuid',$uuid)->first();
        if ($check->resign_date != null) {
            $count = 12;
        } else {
            $count = count($bulan);
        }
        
        $allowance = array_sum($allowances)/$count;
        $deduction = array_sum($deductions)/$count;

        $res['allowance'] = round($allowance,0);
        $res['deduction'] = round($deduction,0);

        return $res;
    }

    public function payroll($id)
    {
        $id = base64_decode($id);

        $data['model'] = PayrollEmployee::find($id);
        // dd($data['model']->payroll->project->name);
        return view('payroll.project.payroll',$data);
    }

    public function payslip($id)
    {
        $id = base64_decode($id);

        $data['model'] = PayrollEmployee::find($id);
        // dd($data['model']->payroll->project->name);
        return view('payroll.project.payslip',$data);
    }

    public function recentUse(Request $request)
    {
        $project = $request->project;
        $month = $request->month;
        $year = $request->year;

        $check = ProjectPayroll::where('project_code',$project)->where('month',$month)->where('year',$year)->get();

        if (count($check) > 0) {
            return 'created';
        } else {
            $data['model'] = ProjectPayroll::where('project_code',$project)->orderByDesc('id')->first();
            if ($data['model']) {
                $variables = ProjectPayrollVariables::where('project_payroll_id',$data['model']->id)->get();
                foreach ($variables as $key => $value) {
                    $variable[] = $value->variable->name;
                    switch ($value->variable->type) {
                        case 1:
                            $data['allowance'][] = $value->variable->name;
                        break;
                        case 2:
                            $data['deduction'][] = $value->variable->name;
                        break;
                        default:
                            # code...
                        break;
                    }
                }
            }
            return view('payroll.project.recentUse',$data)->render();
        }
    }

    public function useIt(Request $request)
    {

        
        $cutoff_salary = explode(' to ',$request->cut_off_salary);
        $exp_salary_start = explode('-',$cutoff_salary[0]);
        $salary_start = $exp_salary_start[2].'-'.$exp_salary_start[1].'-'.$exp_salary_start[0];
        $exp_salary_end = explode('-',$cutoff_salary[1]);
        $salary_end = $exp_salary_end[2].'-'.$exp_salary_end[1].'-'.$exp_salary_end[0];

        $cutoff_allowance = explode(' to ',$request->cut_off_allowance);
        $exp_allowance_start = explode('-',$cutoff_allowance[0]);
        $allowance_start = $exp_allowance_start[2].'-'.$exp_allowance_start[1].'-'.$exp_allowance_start[0];
        $exp_allowance_end = explode('-',$cutoff_allowance[1]);
        $allowance_end = $exp_allowance_end[2].'-'.$exp_allowance_end[1].'-'.$exp_allowance_end[0];

        $exp_payment_date = explode('-',$request->payment_date);
        $payment_date = $exp_payment_date[2].'-'.$exp_payment_date[1].'-'.$exp_payment_date[0];

        $model = new ProjectPayroll();
        $model->project_code = $request->project;
        $model->month = $request->month;
        $model->year = $request->year;
        $model->created_by = Auth::user()->uuid;
        $model->status_id = 1;

        $model->cutoff_salary_start = $salary_start;
        $model->cutoff_salary_end = $salary_end;
        $model->cutoff_allowance_start = $allowance_start;
        $model->cutoff_allowance_end = $allowance_end;
        $model->payment_date = $payment_date;


        if ($model->save()) 
        {
            $recent = ProjectPayrollVariables::where('project_payroll_id',$request->id)->get();
            foreach ($recent as $key => $value) {
                $variable = new ProjectPayrollVariables();
                $variable->project_payroll_id = $model->id;
                $variable->variable_id = $value->variable_id;
                $variable->save();
            }
            $res['res'] = 'success';
            $res['id'] = base64_encode($model->id);
        } else {
            $res['res'] = 'failed';
        }
        return $res;
    }

    public function storeAddVariable(Request $request)
    {
        foreach ($request->variables as $key => $value) {
            $variable = new ProjectPayrollVariables();
            $variable->project_payroll_id = $request->projectPayrollId;
            $variable->variable_id = $value;
            $variable->save();
        }
        return redirect()->route('payroll.project.edit',['id' => base64_encode($request->projectPayrollId)])->with('alert.success', 'Variable Added');

    }

    public function pattern($id)
    {
        ob_end_clean(); // this
        ob_start(); // and this
        set_time_limit(0);
        $variables = ProjectPayrollVariables::where('project_payroll_id',$id)->get();
        $model = ProjectPayroll::find($id);

        foreach ($variables as $key => $value) {
            $variable[] = $value->variable->name;
            switch ($value->variable->type) {
                case 1:
                    $data['allowance'][] = array(
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                    );
                break;
                case 2:
                    $data['deduction'][] = array(
                        'name' => $value->variable->name,
                        'type' => $value->variable->type,
                        'model' => $value->variable->model,
                    );
                break;
                default:
                    # code...
                break;
            }
        }


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'PTKP');
        $sheet->setCellValue('C1', 'Basic Salary');
        $idx = 4;

        foreach ($data['allowance'] as $value) {
            if ($value['model'] == 2) {
                $sheet->setCellValueByColumnAndRow($idx, 1, $value['name'].' (%)');
            } else {
                $sheet->setCellValueByColumnAndRow($idx, 1, $value['name']);
            }
            $idx ++;
        }

        foreach ($data['deduction'] as $value) {
            if ($value['model'] == 2) {
                $sheet->setCellValueByColumnAndRow($idx, 1, $value['name'].' (%)');
            } else {
                $sheet->setCellValueByColumnAndRow($idx, 1, $value['name']);
            }
            $idx ++;
        }

        // $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        $nama = $model->project->name.'-'.$model->month.'-'.$model->year;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function downloadPaylist(Request $request)
    {
        ob_end_clean(); // this
        ob_start(); // and this
        set_time_limit(0);

        $ids = $request->projectPayrolId;
        $spreadsheet = new Spreadsheet();
        $idxSheet = 0;

        if ($ids) {
            foreach ($ids as $key => $id) {
                $value = ProjectPayroll::find($id);
                $name = $value->group->name.' - '.$value->month.' - '.$value->year;
                $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $name);
                $spreadsheet->addSheet($sheet, $idxSheet);
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
                $sheet = $spreadsheet->getSheet($idxSheet);
                $idxSheet++;
    
                $variables = ProjectPayrollVariables::where('project_payroll_id',$value->id)->get();
                $model = ProjectPayroll::find($value->id);
                $sheet->setCellValue('A1', 'Employee ID')->mergeCells('A1:A2');        
                $sheet->getColumnDimension('A')->setWidth(15); //->setAutoSize(true)
                $sheet->setCellValue('B1', 'Employee Name')->mergeCells('B1:B2');        
                $sheet->getColumnDimension('B')->setWidth(20); //->setAutoSize(true)
                $sheet->setCellValue('C1', 'PTKP')->mergeCells('C1:C2');        
                $sheet->getColumnDimension('C')->setWidth(8); //->setAutoSize(true)
                $sheet->setCellValue('D1', 'Basic Salary')->mergeCells('D1:D2');        
                $sheet->getColumnDimension('D')->setWidth(20); //->setAutoSize(true)
                foreach ($variables as $key => $value) {
                    $variable[] = $value->variable->name;
                    switch ($value->variable->type) {
                        case 1:
                            $data['allowance'][] = array(
                                'name' => $value->variable->name,
                                'type' => $value->variable->type,
                                'model' => $value->variable->model,
                            );
                        break;
                        case 2:
                            $data['deduction'][] = array(
                                'name' => $value->variable->name,
                                'type' => $value->variable->type,
                                'model' => $value->variable->model,
                            );
                        break;
                        default:
                            # code...
                        break;
                    }
                }
                $idx = 5;
                $iEnd = 0;
                $sheet->setCellValueByColumnAndRow($idx, 1, 'Allowance');
                foreach ($data['allowance'] as $value) {
                    $iEnd = $idx;
                    if ($value['model'] == 2) {
                        $name = $value['name'].' (%)';
                    } else {
                        $name = $value['name'];
                    }
                    $start = $sheet->getColumnDimensionByColumn($idx)->getColumnIndex();
                    $sheet->setCellValueByColumnAndRow($idx, 2, $name)->getStyle($start . '2')->getAlignment()->setHorizontal('center');
                    $sheet->getColumnDimension($start)->setAutoSize(true);
                    $idx ++;
                }
                $end = $sheet->getColumnDimensionByColumn($iEnd)->getColumnIndex();
                $sheet->mergeCells('E1:' . $end . '1');
        
                $jEnd = 0;
                $jColumn = $sheet->getColumnDimensionByColumn($idx)->getColumnIndex();
                $sheet->setCellValueByColumnAndRow($idx, 1, 'Deduction');
        
                foreach ($data['deduction'] as $value) {
                    $jEnd = $idx;
                    if ($value['model'] == 2) {
                        $name = $value['name'].' (%)';
                    } else {
                        $name = $value['name'];
                    }
                    $start = $sheet->getColumnDimensionByColumn($idx)->getColumnIndex();
                    $sheet->setCellValueByColumnAndRow($idx, 2, $name)->getStyle($start . '2')->getAlignment()->setHorizontal('center');
                    $sheet->getColumnDimension($start)->setAutoSize(true);
                    $idx ++;
                }
                $end = $sheet->getColumnDimensionByColumn($jEnd)->getColumnIndex();
                $sheet->mergeCells($jColumn .'1:' .$end .'1');
                $pphIndex = $sheet->getColumnDimensionByColumn($jEnd + 1)->getColumnIndex();
                $sheet->setCellValueByColumnAndRow($jEnd + 1, 1, 'pph21')->mergeCells($pphIndex .'1:' .$pphIndex .'2');
                $sheet->getColumnDimension($pphIndex)->setWidth(15); //->setAutoSize(true)
                $sheet->getStyle('A1:' . $pphIndex . '2')->applyFromArray($styleHeader);
                
                $employees = PayrollEmployee::where('project_payroll_id',$id)->get();
                $row = 3;
                foreach ($employees as $key => $item) {
                    $sheet->setCellValue('A'.$row, $item->employee->empl_id)->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
                    $sheet->setCellValue('B'.$row, $item->employee->name)->getStyle('B'.$row)->getAlignment()->setHorizontal('center');
                    $sheet->setCellValue('C'.$row, $item->ptkp->name)->getStyle('C'.$row)->getAlignment()->setHorizontal('center');
                    $sheet->setCellValue('D'.$row, $item->basic_salary)->getStyle('D'.$row)->getAlignment()->setHorizontal('center');
    
                    $emplVar = PayrollEmployeeVariables::where('payroll_employee_id',$item->id)->get();
                    $col = 5;
                    foreach ($emplVar as $key => $value) {
                        $sheet->setCellValueByColumnAndRow($col, $row, $value->value);
                        $col ++;
                    }
                    $sheet->setCellValueByColumnAndRow($col, $row, $item->pajak);
                    $row ++; 
                }
    
            }
    
            $nama = 'Payroll List';//$model->project->name.'-'.$model->month.'-'.$model->year;
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$nama.'.xlsx"');
            header('Cache-Control: max-age=0');
    
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } else {
            return redirect()->back()->with('alert.failed', 'Select Minimal One Project');
        }
    }

    public function spt($id)
    {
        $id = base64_decode($id);

        $model = PayrollEmployee::find($id);
        $empl = Employee::find($model->employee_uuid);


        $spt = './spt.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($spt);
        $sheet = $spreadsheet->getSheet(0);
        $sheet->setCellValue('T29', $empl->name);

        $nama = 'SPT '.$empl->name;//$model->project->name.'-'.$model->month.'-'.$model->year;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    
    public function downloadPaylistEmployee (Request $request)
    {
        ob_end_clean(); // this
        ob_start(); // and this
        set_time_limit(0);
        $id = base64_decode($request->projectPayrolId);
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
        $sheet->setCellValueByColumnAndRow(1, 1, 'Project')->mergeCells('A1:A2');
        $sheet->getColumnDimension('A')->setWidth(35); //->setAutoSize(true)
        // $sheet->getStyle('A1:A2')->getFont()->setName('Arial');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Bulan')->mergeCells('B1:B2');
        $sheet->getColumnDimension('B')->setWidth(12); //->setAutoSize(true)
        $sheet->setCellValueByColumnAndRow(3, 1, 'Tahun')->mergeCells('C1:C2');
        $sheet->getColumnDimension('C')->setWidth(8); //->setAutoSize(true)
        $sheet->setCellValueByColumnAndRow(4, 1, 'Employee ID')->mergeCells('D1:D2');
        $sheet->getColumnDimension('D')->setWidth(15); //->setAutoSize(true)
        $sheet->setCellValueByColumnAndRow(5, 1, 'Employee Name')->mergeCells('E1:E2');
        $sheet->getColumnDimension('E')->setWidth(20); //->setAutoSize(true)
        $sheet->setCellValueByColumnAndRow(6, 1, 'PTKP')->mergeCells('F1:F2');
        $sheet->getColumnDimension('F')->setWidth(8); //->setAutoSize(true)
        $sheet->setCellValueByColumnAndRow(7, 1, 'Basic Salary')->mergeCells('G1:G2');
        $sheet->getColumnDimension('G')->setWidth(20); //->setAutoSize(true)

        $projectPayroll = ProjectPayroll::find($id);

        $iStart = 8;
        $iEnd = 0;
        $allowances = ProjectPayrollVariables::where('project_payroll_id',$projectPayroll->id)->whereHas('variable', function($query) {
            $query->where('type', 1)->orWhere('type',3);
        })->get();
        $sheet->setCellValueByColumnAndRow($iStart, 1, 'Allowance');
        foreach ($allowances as $allowance) {
            $iEnd = $iStart;
            $name = ($allowance->variable->model == 2) ? $allowance->variable->name . ' (%)' : $allowance->variable->name;
            $start = $sheet->getColumnDimensionByColumn($iStart)->getColumnIndex();
            $sheet->setCellValueByColumnAndRow($iStart, 2, $name)->getStyle($start . '2')->getAlignment()->setHorizontal('center');
            $sheet->getColumnDimension($start)->setAutoSize(true);
            $iStart++;
        }
        $end = $sheet->getColumnDimensionByColumn($iEnd)->getColumnIndex();
        $sheet->mergeCells('H1:' . $end . '1');

        $end = $sheet->getColumnDimensionByColumn($iStart)->getColumnIndex();
        $sheet->setCellValueByColumnAndRow($iStart, 1, 'Total Allowance');
        $sheet->mergeCells($end.'1:' . $end . '2');

        $jStart = $iStart + 1;
        $jEnd = 0;
        $jColumn = $sheet->getColumnDimensionByColumn($jStart)->getColumnIndex();
        $deductions = ProjectPayrollVariables::where('project_payroll_id',$projectPayroll->id)->whereHas('variable', function($query) {
            $query->where('type', 2)->orWhere('type',4);
        })->get();
        $sheet->setCellValueByColumnAndRow($jStart, 1, 'Deduction');
        foreach ($deductions as $deduction) {
            $jEnd = $jStart;
            $name = ($deduction->variable->model == 2) ? $deduction->variable->name . ' (%)' : $deduction->variable->name;
            $start = $sheet->getColumnDimensionByColumn($jStart)->getColumnIndex();
            $sheet->setCellValueByColumnAndRow($jStart, 2, $name)->getStyle($start . '2')->getAlignment()->setHorizontal('center');
            $sheet->getColumnDimension($start)->setAutoSize(true);
            $jStart++;
        }
        $end = $sheet->getColumnDimensionByColumn($jEnd)->getColumnIndex();
        $sheet->mergeCells( $jColumn. '1:' . $end . '1');

        $end = $sheet->getColumnDimensionByColumn($jStart)->getColumnIndex();
        $sheet->setCellValueByColumnAndRow($jStart, 1, 'Total Deduction');
        $sheet->mergeCells($end.'1:' . $end . '2');

        $pphIndex = $sheet->getColumnDimensionByColumn($jStart + 1)->getColumnIndex();
        $sheet->setCellValueByColumnAndRow($jStart + 1, 1, 'pph21')->mergeCells($pphIndex .'1:' .$pphIndex .'2');
        $sheet->getColumnDimension($pphIndex)->setWidth(15); //->setAutoSize(true)
        $sheet->getStyle('A1:' . $pphIndex . '2')->applyFromArray($styleHeader);

        $employees = PayrollEmployee::where('project_payroll_id',$id)->get();
        $row = 3;
        foreach ($employees as $key => $item) {
            $sheet->setCellValue('A'.$row, $projectPayroll->project->name)->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('B'.$row, $projectPayroll->month)->getStyle('B'.$row)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('C'.$row, $projectPayroll->year)->getStyle('C'.$row)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('D'.$row, $item->employee->empl_id)->getStyle('D'.$row)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('E'.$row, $item->employee->name)->getStyle('E'.$row)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('F'.$row, $item->ptkp->name)->getStyle('F'.$row)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('G'.$row, $item->basic_salary)->getStyle('G'.$row)->getAlignment()->setHorizontal('center');

            $emplVar = PayrollEmployeeVariables::where('payroll_employee_id',$item->id)->get();
            $col = 8;
            $all = 0;
            foreach ($emplVar as $key => $value) {
                if ($value->variable->type == 1 || $value->variable->type == 3) {
                    $sheet->setCellValueByColumnAndRow($col, $row, round($value->value));
                    $all += $value->value;
                    $col ++;
                }

            }
            $sheet->setCellValueByColumnAndRow($col, $row, round($all));
            $col ++;
            $ded = 0;
            $emplVar = PayrollEmployeeVariables::where('payroll_employee_id',$item->id)->get();
            foreach ($emplVar as $key => $value) {
                if ($value->variable->type == 2 || $value->variable->type == 4) {
                    $sheet->setCellValueByColumnAndRow($col, $row, round($value->value));
                    $ded += $value->value;
                    $col ++;
                }
            }
            $sheet->setCellValueByColumnAndRow($col, $row, round($ded));
            $col ++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->pajak);
            $row ++; 
        }

        $nama = 'Payroll List Employee';//$model->project->name.'-'.$model->month.'-'.$model->year;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function destroyPayroll($id)
    {
        $id = base64_decode($id);

        PayrollEmployeeVariables::where('payroll_employee_id',$id)->delete();
        PayrollEmployee::destroy($id);
    }

}
