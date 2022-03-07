<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FieldJob
{
    public static function get()
    {
        return DB::connection('HRFDB')
            ->table('field_job')
            ->whereNull('exp_dt')
            ->orderBy('job_nm', 'asc')
            ->get(['field_job_id', 'job_nm']);
    }

    public static function getById($client_id)
    {
        return DB::connection('HRFDB')
            ->table('mst_client')
            ->whereNull('exp_dt')
            ->where('client_id', '=', $client_id)
            ->first();
    }
}
