<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $purchaseTypes = PurchaseType::all();
        $stocks = [];
        foreach ($purchaseTypes as $type) {
            $query = DB::table('orbit_stocks')
                        ->join('routers', 'orbit_stocks.router_id', '=', 'routers.id')
                        ->join('po_dtl', 'routers.po_dtl_id', '=', 'po_dtl.id')
                        ->join('material', 'po_dtl.material_id', '=', 'material.id')
                        ->join('simcards', 'orbit_stocks.simcard_id', '=', 'simcards.id')
                        ->join('purchase_types', 'orbit_stocks.purchase_type_id', '=', 'purchase_types.id')
                        ->where('orbit_stocks.status_id', '=', 2)
                        ->where('orbit_stocks.purchase_type_id', '=', $type->id)
                        ->select([
                            'material.name AS name', 'simcards.msisdn',
                            'routers.esn', 'routers.imei', 'routers.ssid', 'routers.password_router', 'routers.guest_ssid', 'routers.password_admin'
                        ]);
            $data = $query->get();
            $stocks[] = [
                'type' => strtoupper($type->name),
                'total' => count($data),
                'data' => $data,
            ];
        }
        $response = [
            'message' => 'success',
            'stocks' => $stocks
        ];
        return response()->json($response, 200);
    }
}
