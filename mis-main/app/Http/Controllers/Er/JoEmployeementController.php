<?php

namespace App\Http\Controllers\Er;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\Employee;
use App\Models\Er\Candidate;
use App\Models\Er\Company;
use App\Models\Er\Employee as ErEmployee;
use App\Models\Er\EmployeeHist;
use App\Models\Er\EmployeeProject;
use App\Models\Er\Project;
use App\Models\Er\Role;
use App\Models\Er\RoleEmployee;
use App\Services\Client;
use App\Services\FieldJob;
use App\Services\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class JoEmployeementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $data['clients']      = Client::get();
        $data['field_jobs']   = FieldJob::get();
        return view('er.jo.employeement.list', $data);
    }

    public function confirmation(Request $request)
    {
        // $data['clients']      = Client::get();
        // $data['field_jobs']   = FieldJob::get();
        $empls = [];
        $jo_id = [];
        // dd($request->empl);
        if ($request->empl) {
            foreach ($request->empl as $key_0 => $value_0) {
                $empls[] = $key_0;
                $jo_id[] = $value_0;
                // $candidate['cand_id'] = $key_0;
                // $candidate['jo_id'] = $value_0;
            }
        }
        $queryJobEmployeement = Job::emp();
        if (count($empls) > 0) {
            $queryJobEmployeement->whereIn('je.cand_id', $empls);
        }
        $data['empls'] = $queryJobEmployeement->get();
        $data['er'] = Employee::join('role_employee as re', 'employees.uuid ', '=', 're.employee_uuid')
                        ->join('roles as r', 're.role_id ', '=', 'r.id')
                        ->where('r.department_code', '=', 'ER')
                        ->get(['employees.uuid', 'employees.name']);
        return view('er.jo.employeement.confirmation', $data);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

   public function list(Request $request)
   {
        // $laratrust = new Laratrust(app());
    //    $canSchedule = $laratrust->can('schedule-req-vehicle');
    //    $canUpdate = $laratrust->can('update-req-vehicle');
    //    $canDelete = $laratrust->can('delete-req-vehicle');
       
        $queryJobEmployeement = Job::get();

        if ($request->client != "null") {
            $queryJobEmployeement->where("mcli.client_id", "=", $request->client);
        }

        if ($request->job_field != "null") {
            $queryJobEmployeement->where("fj.field_job_id", "=", $request->job_field);
        }
        // $laratrust->user()->roles;
        return Datatables::of($queryJobEmployeement)
            ->addColumn('action', function ($model) {
                $string = '<div class="form-check"> <input class="form-check-input check-empl" type="checkbox" name="empl['.$model->cand_id.']" value="'.$model->jo_id.'"></div>';
                return $string;
            })
            ->editColumn('project', function ($model) {
                return $model->client_nm . " - " .$model->job_nm;
            })
            ->rawColumns(['action'])    
            ->make(true);
   }

   public function listConfirmation(Request $request)
   {
        // $laratrust = new Laratrust(app());
    //    $canSchedule = $laratrust->can('schedule-req-vehicle');
    //    $canUpdate = $laratrust->can('update-req-vehicle');
    //    $canDelete = $laratrust->can('delete-req-vehicle');
       
        $queryJobEmployeement = Job::emp();
        $cand_id = $request->cand_id;
        if (count($cand_id) > 0) {
            $queryJobEmployeement->whereIn('je.cand_id', $cand_id);
        }

        if ($request->jo_id != "null") {
            $queryJobEmployeement->where("JO.jo_id", "=", $request->jo_id);
        }
        // $laratrust->user()->roles;
        return Datatables::of($queryJobEmployeement)
            ->make(true);
   }

   public function store(Request $request)
   {
    //    dd(count($request->employees), $request->jo_id, $request->er_empl);
        $failed_empl = [];
        $success_empl = [];
        $departement_code = "";
        $count_empl = count($request->employees);
        $count_failed = 0;
        $count_success = 0;
        $comp_id = "";
        foreach ($request->employees as $key =>$value) {
            // dd($request->jo_id[$key]);
            $job_empl = Job::emp()->where("je.cand_id", "=", $value)->where("JO.jo_id", "=", $request->jo_id[$key])->first();
            // dd($job_empl->client_id);
            $client_group = Client::getClientGroup($job_empl->client_id);
            $comp_id = "";
            $company = Company::where('name', $client_group->group_nm)->first();
            if (!$company) {
                $com = new Company();
                $com->name = $client_group->group_nm;
                $com->save();

                $comp_id = $com->id;
            }else {
                $comp_id = $company->id;
            }
            $departement_code = $job_empl->client_id."-".$job_empl->job_field_cd."-".$job_empl->field_job_id;
            $departement_name = $job_empl->client_nm." - ".$job_empl->job_nm;
            $project = Project::where('name', $departement_name)->first();
            if (!$project) {
                $proj = new Project();
                $proj->code = $departement_code;
                $proj->name = $departement_name;
                $proj->company_id = $comp_id;
                $proj->save();

                $project_nm = $proj->name;
            }else {
                $departement_code = $project->code;
                $project_nm = $project->name;
            }
            $roles_name = $departement_code."-".$job_empl->job_pos_cd;
            $roles = Role::where('department_code', $departement_code)->where('name',$roles_name)->first();
            $role_id = ($roles) ? $roles->id : "";
            if (!$roles) {
                $role = new Role();
                $role->department_code = $departement_code;
                $role->name = $roles_name;
                $role->display_name = $job_empl->job_pos_nm;
                $role->description = $job_empl->client_nm." - ".$job_empl->job_nm." ".$job_empl->job_pos_nm;
                $role->save();

                $role_id = $role->id;
            }
            // name, email, mobile_no, dob, password, cand_id, department_code
            $employee = ErEmployee::withTrashed()->where('cand_id', $value)->first();
            $candidate = Candidate::withTrashed()->where('cand_id',$value)->first();
            if (!$employee) {
                $empl = new ErEmployee();
                $empl->name = $candidate->full_nm;
                $empl->email = $candidate->email;
                $empl->mobile_no = $candidate->mob_no;
                $empl->dob = $candidate->birth_dt;
                $empl->password = $candidate->pwd;
                $empl->cand_id = $value;
                $empl->department_code = $departement_code;
                $empl->company_id = $comp_id;

                if ($empl->save()) {
                    $role_empl = new RoleEmployee();
                    $role_empl->role_id = $role_id;
                    $role_empl->employee_uuid = $empl->uuid;
                    $role_empl->user_type = "App\Models\Auth\Employee";

                    $role_empl->save();

                    $success_empl[] = ['cand_id' => $value, 'full_nm' => $candidate->full_nm, 'email' => $candidate->email, 'jo_id' => $request->jo_id[$key], 'position' => $job_empl->job_pos_nm, 'project' => $project_nm];
                    $count_success++;
                    
                    Job::emplEmployeed($job_empl->jo_empl_id, $value, $request->jo_id[$key]);
                } else {
                    $failed_empl[] = ['cand_id' => $value, 'full_nm' => $candidate->full_nm, 'email' => $candidate->email, 'jo_id' => $request->jo_id[$key], 'position' => $job_empl->job_pos_nm, 'project' => $project_nm];
                    $count_failed++;
                }   
            }else {
                // dd($employee->uuid);
                $empl_hist = new EmployeeHist();
                $empl_hist->employee_uuid = $employee->uuid;
                $empl_hist->department_code = $employee->department_code;
                $empl_hist->role_id = $role_id;
                $empl_hist->company_id = $employee->company_id;
                $empl_hist->empl_id = $employee->empl_id;
                $empl_hist->join_date = $employee->join_date;
                $empl_hist->created_at = now();
                $empl_hist->created_by = Auth::user()->uuid;
                if ($empl_hist->save()) {
                    $employeeEr = ErEmployee::withTrashed()->Find($employee->uuid);
                    $employeeEr->cand_id = $value;
                    $employeeEr->department_code = $departement_code;
                    $employeeEr->company_id = $comp_id;
                    $employeeEr->deleted_at = NULL;
                    if ($employeeEr->save()) {
                        if ($employeeEr->roleEmployee) {
                            $role_empl = RoleEmployee::where('employee_uuid', $employeeEr->uuid)->update(['role_id' => $role_id]);
                        } else {
                            $role_empl = new RoleEmployee();
                            $role_empl->role_id = $role_id;
                            $role_empl->employee_uuid = $employee->uuid;
                            $role_empl->user_type = "App\Models\Auth\Employee";
                            
                            $role_empl->save();
                        }
    
                        $success_empl[] = ['cand_id' => $value, 'full_nm' => $candidate->full_nm, 'email' => $candidate->email, 'jo_id' => $request->jo_id[$key], 'position' => $job_empl->job_pos_nm, 'project' => $project_nm];
                        $count_success++;
                        
                        Job::emplEmployeed($job_empl->jo_empl_id, $value, $request->jo_id[$key]);
                    } else {
                        $failed_empl[] = ['cand_id' => $value, 'full_nm' => $candidate->full_nm, 'email' => $candidate->email, 'jo_id' => $request->jo_id[$key], 'position' => $job_empl->job_pos_nm, 'project' => $project_nm];
                        $count_failed++;
                    }   
                }
            }
        }
        // dd($request->er_empl);
        // dd($departement_code);
        if ($departement_code != "") {
            $employee_project = EmployeeProject::create([
                'employee_uuid' => $request->er_empl,
                'project_code' => $departement_code
            ]);
            $employee_project->save();
        }
        $data['count_employeement'] = $count_empl;
        $data['success_empl'] = $success_empl;
        $data['count_success'] = $count_success;
        $data['failed_empl'] = $failed_empl;
        $data['count_failed'] = $count_failed;
        $data['alert_success'] = 'Employeement Success';
        return view('er.jo.employeement.success', $data);
   }

   public function employeed()
   {
       $data['companies'] = Company::all();

       return view('er.jo.employeement.employeed', $data);
   }

   public function employeedList(Request $request)
   {   

        $queryJobEmployeement = ErEmployee::join('departments as dept', 'dept.code ', '=', 'employees.department_code')
        ->join('role_employee as re', 're.employee_uuid', '=', 'employees.uuid')
        ->join('roles as r', 'r.id', '=', 're.role_id')
        ->join('companies as c', 'employees.company_id', '=', 'c.id')
        ->select('employees.uuid', 'employees.name', 'employees.email', 'employees.empl_id', 'dept.name as project', 'r.display_name as position', 'employees.join_date', 'employees.cand_id', 'employees.bank_account', 'employees.npwp', 'c.name as comp_name');

        if ($request->client != "") {
            $queryJobEmployeement->where("employees.company_id", "=", $request->client);
        }

        if ($request->job_field != "") {
            $queryJobEmployeement->where("employees.department_code", "=", $request->job_field);
        }
       // $laratrust->user()->roles;
        return Datatables::of($queryJobEmployeement->get())
            ->editColumn('join_date', function ($model) {
                $join_date = ($model->join_date != '') ? date_create($model->join_date) : '';
                $format = ($join_date != '') ? date_format($join_date,"d-m-Y") : '-';
                return $format;
            })
            ->editColumn('project', function ($model){
                $project = $model->position.' | '.$model->project;
                return $project;
            })
            ->addColumn('action', function ($model) {
                $string = '<a href="'.route('er.jo.employeement.edit',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-info modal-show edit" title="Edit Employee"><i class="fa fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="'.route('er.jo.employeement.release',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-danger modal-show edit" title="Release Employee"><i class="fa fa-times"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="'.route('er.jo.employeement.cancel',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-warning btn-action" title="Cancel Employee"><i class="fa fa-trash"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="'.route('er.jo.employeement.resetPassword',['id' => base64_encode($model->uuid)]).'" type="button" class="btn btn-xs btn-primary btn-action" title="Reset Password"><i class="fas fa-redo-alt"></i></a>';
                return $string;
            })
            ->rawColumns(['action'])    
            ->make(true);
   }

   public function edit($id)
   {
        $id     = \base64_decode($id);
        $data['employee']   = ErEmployee::find($id);

        return view('er.jo.employeement.edit', $data);
   }

   public function update(Request $request)
   {
        $id = base64_decode($request->id);
        
        $employee = ErEmployee::findOrFail($id);
        $employee->name = $request->name;
        
        $this->validate($request, [
            'empl_id' => [Rule::unique('MESDB.employees', 'empl_id')->ignore($employee->uuid, 'uuid')]
        ]);

        $employee->empl_id = $request->empl_id;
        $employee->bank_account = $request->bank_account;
        $employee->npwp = $request->npwp;
        $date = $request->date;

        if ($date) {
            $date = explode('-', $date);
            $employee->join_date = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $employee->save();
   }

   public function resetPassword($id)
   {
        $id = \base64_decode($id);
        $empl       = ErEmployee::find($id);
        $empl->password = '25d55ad283aa400af464c76d713c07ad';
        $empl->save();
   }

   public function release($id)
   {
        $id     = \base64_decode($id);
        $data['employee']   = ErEmployee::find($id);

        return view('er.jo.employeement.release', $data);
   }

   public function releaseProcess(Request $request)
   {
        $id = \base64_decode($request->id);
        $date = $request->date;
        $empl       = ErEmployee::find($id);

        if ($date) {
            $date = explode('-', $date);
            $empl->resign_date = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $empl->resign_reason = $request->resign_reason;
        if ($empl->save()) {
            $empl->delete();
            
            if($empl->cand_id){
                $hrfdb = DB::connection('HRFDB');
                $rls_tm = date('Y-m-d H:i:s');
                try {
                    $hrfdb->beginTransaction();
                    
                    $hrfdb->table('jo_empl')
                    ->where('cand_id', '=', $empl->cand_id)
                    ->update([
                        'empl_proc_stat_cd' => 'LS',
                        'rls_note' => 'Release from MIS',
                        'rls_tm' => $rls_tm
                    ]);
                    
                    $hrfdb->table('mst_candidate')
                    ->where('cand_id', '=', $empl->cand_id)
                    ->update([
                        'proc_stat_cd' => 'FI'
                    ]);
                    
                    $hrfdb->commit();
                    return true;
                } catch (\Throwable $e) {
                    dd($e);
                    $hrfdb->rollBack();
                    return false;
                }
            }
        }
   }

   public function cancel($id)
   {
        $id         = \base64_decode($id);
        $empl       = ErEmployee::find($id);
        $empl->delete();

        // $role_empl       = RoleEmployee::where('employee_uuid', $id);
        // $role_empl->delete();

        if($empl->cand_id){
            $hrfdb = DB::connection('HRFDB');
            $rls_tm = date('Y-m-d H:i:s');
            try {
                $hrfdb->beginTransaction();
        
                $hrfdb->table('jo_empl')
                ->where('cand_id', '=', $empl->cand_id)
                ->update([
                    'empl_proc_stat_cd' => 'LS',
                    'rls_note' => 'cancel from MIS',
                    'rls_tm' => $rls_tm
                ]);
        
                $hrfdb->table('mst_candidate')
                ->where('cand_id', '=', $empl->cand_id)
                ->update([
                    'proc_stat_cd' => 'FI'
                ]);

                $hrfdb->commit();
                return true;
            } catch (\Throwable $e) {
                dd($e);
                $hrfdb->rollBack();
                return false;
            }
        }
   }
}
