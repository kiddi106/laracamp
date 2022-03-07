<?php

namespace App\Services;

use App\Models\Auth\Employee as AuthEmployee;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\DB;


class Employee
{

  public static function getEmployee($start, $end, $role_id)
  {

    if (count($role_id) > 1) {
      $where = 'where id = '.$role_id[0]->id.' or id = '.$role_id[1]->id;
      
    }
    else
    {
      $where = 'where id = '.$role_id[0]->id;
    }

    return DB::select("WITH role AS 
        (
         SELECT a.id, a.parent_id, a.name,a.department_code,a.display_name
         FROM roles a
         ".$where."
         UNION ALL
         SELECT a.id, a.parent_id, a.Name,a.department_code,a.display_name
         FROM roles a 
           JOIN role c ON a.parent_id = c.id
         )
         SELECT r.parent_id, r.id, r.name,emp.name as emp_name,emp.uuid,att.*,r.department_code,dpr.name as department_name,emp.empl_id,emp2.name as direct_leader,r.display_name
         FROM role r
           join role_employee re on r.id=re.role_id
           join employees emp on re.employee_uuid = emp.uuid
           join attendances att on att.employee_uuid = emp.uuid
           join departments dpr on r.department_code = dpr.code
           left join employees emp2 on emp.parent_uuid = emp2.uuid
           where att.date between '" . $start . "' and '" . $end . "'
           and emp.uuid not in ('F1F64912-6E8D-4784-9815-5CA71170329E')
           ");
    
    // $roles = AuthEmployee::roleChilds();
    // $emplAtt = [];
    // foreach ($roles as $role) {
    //   $att = DB::select("SELECT r.parent_id, r.id, r.name,emp.name as emp_name,emp.uuid,att.*,r.department_code,dpr.name as department_name,emp.empl_id,emp2.name as direct_leader,r.display_name
    //      FROM roles r
    //        join role_employee re on r.id=re.role_id
    //        join employees emp on re.employee_uuid = emp.uuid
    //        join attendances att on att.employee_uuid = emp.uuid
    //        join departments dpr on r.department_code = dpr.code
    //        left join employees emp2 on emp.parent_uuid = emp2.uuid
    //        where att.date between '" . $start . "' and '" . $end . "'
    //        and emp.uuid not in ('F1F64912-6E8D-4784-9815-5CA71170329E')
    //        and r.id = '".$role."'
    //        ");
    //   array_push($emplAtt, ...$att);
    // }
    // return $emplAtt;
  }

  public static function employeePermissions($role_id,$type,$true,$uuid,$start,$end)
  {
    if (!$true) {
      $where = " and req_permission.employee_uuid = '".$uuid."' ";
    }
    else
    {
      $where = "";
    }

    if ($start != '' && $end != '') 
    {
      $tanggal = " and req_permission.req_date between '" . $start . "' and '" . $end . " '";
    }
    else
    {
      $tanggal = " ";
    }

    return DB::select("WITH role AS 
    (
     SELECT a.id, a.parent_id, a.name,a.department_code,a.display_name
     FROM roles a
     WHERE id = " . $role_id . "
     UNION ALL
     SELECT a.id, a.parent_id, a.Name,a.department_code,a.display_name
     FROM roles a 
       JOIN role c ON a.parent_id = c.id
     )
     SELECT r.parent_id, r.id, r.name,emp.name as emp_name,emp.uuid,r.department_code,dpr.name as department_name,emp.empl_id,r.display_name,req_permission.*,mtp.type_permission_name as type_permission,ms.name as status
     FROM role r
       join role_employee re on r.id=re.role_id
       join employees emp on re.employee_uuid = emp.uuid
       join departments dpr on r.department_code = dpr.code
       join req_permission on req_permission.employee_uuid = emp.uuid
       join mst_type_permission as mtp on req_permission.type_permission_cd = mtp.type_permission_cd
       join mst_status as ms on req_permission.status_id = ms.id
       where mtp.category_permission_cd = '" . $type . "'".$tanggal.$where." ORDER BY req_permission.created_at DESC");
  }
}
