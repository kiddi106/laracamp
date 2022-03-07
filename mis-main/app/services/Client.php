<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Client
{
    public static function get()
    {
        return DB::connection('HRFDB')
            ->table('mst_client')
            ->whereNull('exp_dt')
            ->orderBy('client_nm', 'asc')
            ->get(['client_id', 'client_nm']);
    }

    public static function getById($client_id)
    {
        return DB::connection('HRFDB')
            ->table('mst_client')
            ->whereNull('exp_dt')
            ->where('client_id', '=', $client_id)
            ->first();
    }

    public static function getClientGroup($client_id)
    {
        return DB::connection('HRFDB')
            ->table('mst_client_group')
            ->where('client_id', '=', $client_id)
            ->first();
    }
}
