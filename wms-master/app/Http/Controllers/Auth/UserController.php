<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{/**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:read-auth-user', [
            'only' => ['index', 'datatable']
        ]);
        $this->middleware('permission:create-auth-user', [
            'only' => ['create', 'store']
        ]);
        $this->middleware('permission:update-auth-user', [
            'only' => ['edit', 'update']
        ]);
        $this->middleware('permission:delete-auth-user', [
            'only' => ['destroy']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new User();
        return view('auth.user.form', ['model' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        $query = User::query();
        if ($request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->email) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }
        if ($request->role_id) {
            $role_id = $request->role_id;
            $query->whereHas('roles', function ($q) use ($role_id) {
                $q->whereIn('id', $role_id);
            });
        }
        return DataTables::of($query)
            ->addColumn('roles', function ($model) {
                $data['roles'] = $model->roles;
                return view('auth.user.role', $data);
            })
            ->addColumn('action', function ($model) {
                return 'action';
            })
            ->addIndexColumn()
            ->rawColumns(['roles','action'])
            ->make(true);
    }
}
