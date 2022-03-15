<?php

namespace App\Http\Controllers;

use App\Models\Po;
use App\Models\PoDtl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('po.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $po = new Po();
        return view('po.form', ['po' => $po]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $po = new Po($request->all());
        $po->created_by = Auth::user()->id;

        $retval = true;

        if ($po->save()) {
            $po_dtls = $request->po_dtls;
            if (is_array($po_dtls)) {
                foreach ($po_dtls as $po_dtl) {
                    if ($retval === true) {
                        $dtl = new PoDtl($po_dtl);
                        $dtl->po_id = $po->id;
                        $dtl->description = '';
                        $dtl->created_by = Auth::user()->id;
                        if (!$dtl->save()) {
                            $retval = false;
                        }
                    }
                }
            }
        } else {
            $retval = false;
        }

        if ($retval) {
            return redirect()->route('po.edit',[$po->id])->with('alert.success', 'Data has Been Saved');
        }

        if ($po->id) {
            PoDtl::query()
                ->where('po_id', '=', $po->id)
                ->update([
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'deleted_by' => Auth::user()->id
                ]);
            $po->delete();
        }
        return redirect()->back()->with('alert.failed', 'Something wrong');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $po = Po::findOrFail($id);
        return view('po.form', ['po' => $po]);
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
        $po = Po::findOrFail($id);
        $po->po_no = $request->po_no;
        $po->po_at = $request->po_at;
        $po->delivery_no = $request->delivery_no;
        $po->receive_at = $request->receive_at;
        $po->description = $request->description;
        if ($po->save()) {
            return redirect()->back()->with('alert.success', 'Data has been Updated');
        }
        return redirect()->back()->with('alert.failed', 'Something Error');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function datatable(Request $request)
    {
        $po = Po::query();
        return DataTables::eloquent($po)
            ->editColumn('po_at', function ($po) {
                return date('d/M/Y', strtotime($po->po_at));
            })
            ->editColumn('receive_at', function ($po) {
                return date('d/M/Y', strtotime($po->receive_at));
            })
            ->editColumn('created_at', function ($po) {
                return date('d/M/Y H:i:s', strtotime($po->created_at));
            })
            ->addColumn('created_name', function ($po) {
                return $po->created_user->name;
            })
            ->addColumn('action', function($po) {
                $html = '<a href="' . route('po.edit', ['id' => $po->id]) . '" class="btn btn-xs btn-default" title="edit"><i class="fas fa-edit"></i></a>';
                return $html;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
