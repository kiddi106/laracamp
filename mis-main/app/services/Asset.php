<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;


class Asset
{
    public static function getAsset()
    {
        return DB::table('asset as ass');
    }

    public static function getStatus()
    {
        return DB::table('asset_status');
    }
    public static function getDepartment()
    {
        return DB::table('asset_department');
    }
    public static function getCheckOut()
    {
        return DB::table('asset_checkout_log');
    }

    public static function search(
        int $pageNumber,
        int $rowPerPage,
        $asset_no,
        $serial_no,
        $category_id,
        $status,
        $brand_id,
        $modelType_id,
        $startdate_arrived,
        $enddate_arrived,
        $startdate_handover,
        $enddate_handover
    ) {
        $query = Asset::getAsset();
        $query->leftJoin('asset_transaksi as tr', 'ass.id', '=', 'tr.asset_id');
        $query->leftJoin('asset_status as st', 'st.status_id', '=', 'tr.status_id');
        $query->leftjoin('asset_checkin_log as cil', 'cil.id', '=', 'tr.checkin_id');
        $query->leftjoin('asset_checkout_log as col', 'tr.checkout_id', '=', 'col.checkout_id');
        $query->leftJoin('asset_brands as br', 'ass.brand_id', '=', 'br.brand_id');
        $query->leftJoin('asset_category as c', 'ass.category_id', '=', 'c.category_id');
        $query->leftJoin('asset_model_type as mt', 'ass.model_type_id', '=', 'mt.model_type_id');
        $query->leftJoin('asset_employee as emp', 'col.checkout_to', '=', 'emp.uuid');
        $query->leftJoin('asset_department as dep', 'col.department_code', '=', 'dep.code');
        $query->select([
            'ass.*', 'st.name as status_nm', 'st.parent_id', 'cil.id as checkin_id', 'cil.checkin_from',
            'cil.checkin_at as checkin_dt', 'cil.file_nm as checkin_file', 'emp.name as checkout_to_nm', 'col.checkout_at',
            'br.brand_nm', 'c.category_nm', 'mt.model_type_nm', 'dep.name as department_nm'
        ]);

        if ($asset_no) {
            $query->where('asset_no', 'LIKE', '%' . $asset_no . '%');
        }
        if ($serial_no) {
            $query->where('serial_no', 'LIKE', '%' . $serial_no . '$');
        }
        if ($category_id) {
            $query->where('ass.category_id', '=', $category_id);
        }
        if ($status) {
            $query->where('tr.status_id', '=', $status);
        }
        if ($brand_id) {
            $query->where('ass.brand_id', '=', $brand_id);
        }
        if ($modelType_id) {
            $query->where('ass.model_type_id', '=', $modelType_id);
        }
        if ($startdate_arrived && $enddate_arrived) {
            $query->where('goods_arrived_dt', '>=', $startdate_arrived);
            $query->where('goods_arrived_dt', '<=', $enddate_arrived);
        }
        if ($startdate_handover && $enddate_handover) {
            $query->where('tr.status_id', '=', '6')->orWhere('st.parent_id', '=', '6');
            $query->where('cil.checkin_at', '>=', $startdate_handover);
            $query->where('cil.checkin_at', '<=', $enddate_handover);
        }

        $totalPage = 0;
        $totalData = 0;
        $row = $query->count();
        if ($row != 0) {
            if ($row <= $rowPerPage) {
                $pageNumber = 1;
            }
            $totalData = $row;
            $totalPage = ceil($totalData / $rowPerPage);
            // $totalPage = 8;
        }
        $offset = ($pageNumber * $rowPerPage) - $rowPerPage;
        $query->offset($offset)->limit($rowPerPage);

        $asset = $query->get();
        $count = $asset->count();

        return [
            'page' => [
                'pageNumber' => $pageNumber,
                'rowPerPage' => $rowPerPage,
                'totalPage' => $totalPage,
                'totalData' => $totalData,
                'offset' => $offset,
                'showData' => $count
            ],
            'assets' => $asset
        ];
    }
}
