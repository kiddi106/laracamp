<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Job
{
    public static function get()
    {
        // $empl = DB::connection('MESDB')->table('employees')->whereNull('deleted_at')->whereNotNull('cand_id')->pluck('cand_id')->all();
        $query = Job::emp()->whereNotIn("je.cand_id", function ($query) {
            $query->select('cand_id')->from('MESDB.dbo.employees')->whereNull('deleted_at')->whereNotNull('cand_id');
        });
        // ->whereNotIn('cust', DB::table('customer')->pluck('cust_name'))
        // dd($empl);
        // return DB::connection('HRFDB')
        //     ->table('jo_empl as je')
        //     ->join('mst_candidate as mc', 'je.cand_id ', '=', 'mc.cand_id')
        //     ->leftJoin('candidate_prop as cp', 'je.cand_id ', '=', 'cp.cand_id')
        //     ->leftJoin('job_order as JO', 'je.jo_id ', '=', 'jo.jo_id')
        //     ->leftJoin('mst_client as mcli', 'JO.client_id ', '=', 'mcli.client_id')
        //     ->leftJoin('field_job as fj', 'JO.field_job_id ', '=', 'fj.field_job_id')
        //     ->leftJoin('mst_job_position as mjp', 'JO.job_pos_cd ', '=', 'mjp.job_pos_cd')
        //     ->leftJoin('province_city as pc', 'pc.city_id ', '=', 'jo.city_id')
        //     ->whereRaw("(je.empl_proc_stat_cd = 'EM' or je.empl_proc_stat_cd is NULL) ")
        //     ->whereNotIn("je.cand_id", $empl)
        //     ->select(['je.jo_empl_id','je.cand_id','JO.jo_id as jo_id','mc.full_nm','mc.email', 'mcli.client_id as client_id', 'mcli.client_nm', 'fj.field_job_id', 'fj.job_field_cd', 'fj.job_nm', 'mjp.job_pos_cd', 'mjp.job_pos_nm', 'jo.city_id', 'jo.address','pc.city_nm'])
        //     ->groupByRaw('je.jo_empl_id, je.cand_id, JO.jo_id, mc.full_nm, mc.email, mcli.client_id, mcli.client_nm, fj.field_job_id, fj.job_field_cd, fj.job_nm, mjp.job_pos_cd, mjp.job_pos_nm, jo.city_id, jo.address,pc.city_nm');

        return $query;
    }

    public static function emp()
    {
        // $empl = DB::connection('MESDB')->table('employees')->whereNull('deleted_at')->whereNotNull('cand_id')->pluck('cand_id')->all();
        // dd($empl);
        return DB::connection('HRFDB')
            ->table('jo_empl as je')
            ->join('mst_candidate as mc', 'je.cand_id ', '=', 'mc.cand_id')
            ->leftJoin('candidate_prop as cp', 'je.cand_id ', '=', 'cp.cand_id')
            ->leftJoin('job_order as JO', 'je.jo_id ', '=', 'jo.jo_id')
            ->leftJoin('mst_client as mcli', 'JO.client_id ', '=', 'mcli.client_id')
            ->leftJoin('field_job as fj', 'JO.field_job_id ', '=', 'fj.field_job_id')
            ->leftJoin('mst_job_position as mjp', 'JO.job_pos_cd ', '=', 'mjp.job_pos_cd')
            ->leftJoin('province_city as pc', 'pc.city_id ', '=', 'jo.city_id')
            ->whereRaw("(je.empl_proc_stat_cd = 'EM' or je.empl_proc_stat_cd is NULL) ")
            // ->whereNotIn("je.cand_id", $empl)
            ->select(['je.jo_empl_id','je.cand_id','JO.jo_id as jo_id','mc.full_nm','mc.email', 'mcli.client_id as client_id', 'mcli.client_nm', 'fj.field_job_id', 'fj.job_field_cd', 'fj.job_nm', 'mjp.job_pos_cd', 'mjp.job_pos_nm', 'jo.city_id', 'jo.address','pc.city_nm'])
            ->groupByRaw('je.jo_empl_id, je.cand_id, JO.jo_id, mc.full_nm, mc.email, mcli.client_id, mcli.client_nm, fj.field_job_id, fj.job_field_cd, fj.job_nm, mjp.job_pos_cd, mjp.job_pos_nm, jo.city_id, jo.address,pc.city_nm');
    }

    public static function emplEmployeed($jo_empl_id, $cand_id, $jo_id)
    {
        $upd_tm = date('Y-m-d H:i:s');

        $db = DB::connection('HRFDB');
        try {
            $db->beginTransaction();

            $db->table('jo_empl')
            ->where('jo_empl_id', '=', $jo_empl_id)
            ->where('jo_id', '=', $jo_id)
            ->where('cand_id', '=', $cand_id)
            ->update([
                'empl_proc_stat_cd' => 'EM',
                'upd_tm' => $upd_tm
            ]);

            $db->table('mst_candidate')
            ->where('cand_id', '=', $cand_id)
            ->update([
                'proc_stat_cd' => 'EM',
                'upd_tm' => $upd_tm
            ]);
            
            $db->commit();
            $res = true;
        } catch (\Throwable $e) {
            $db->rollBack();
            $res = false;
        }

        return $res;
    }
}
