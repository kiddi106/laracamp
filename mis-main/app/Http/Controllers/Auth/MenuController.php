<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use App\Models\Auth\Menu;
use App\Models\Auth\MenuRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\Laratrust;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{

    public function index()
    {
        return view('auth.menus.list');
    }

    public function list()
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        

        return Datatables::of(Menu::all())
        ->addColumn('action', function ($model) use ($canUpdate, $canDelete) {

            $string = '<div class="btn-group">';
            if ($canUpdate) {
                $string .= '<a href="'.route('config.menu.edit',['acc_id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-primary modal-show edit" title="Edit"><i class="fa fa-edit"></i></a>';
            }
            if ($canDelete) {
                $string .= '&nbsp;&nbsp;<a href="'.route('config.menu.remove',['acc_id' => base64_encode($model->id)]).'" type="button" class="btn btn-xs btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
            }
            $string .= '</div>';
            return
                $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['menu']   = Menu::get();
        $data['roles'] = Role::get();

        return view('auth.menus.create',$data);
    }




    public function store(Request $request)
    {
        $data['role']   = $request->role;

        $menu = new \App\Models\Auth\Menu();
        $menu->name = $request->name;
        $menu->menu_id = $request->parent;
        $menu->url = $request->url;
        $menu->icon = $request->icon;
        $menu->order_no = $request->order_no;
        $menu->level = $request->level;
        $menu->created_at = date('Y-m-d H:i:s');
        $menu->save();

        foreach ($data['role'] as $key => $value) 
        {
            $menuRole = new \App\Models\Auth\MenuRole();
            $menuRole->role_id = $value;
            $menuRole->menu_id = $menu->id;
            $menuRole->save();
        }

        dd($menu->save());

    }


    public function edit($id)
    {
        $id = \base64_decode($id);

        $data['roles'] = Role::get();

        $data['menu'] = Menu::find($id);

        $data['menus'] = Menu::get();

        $data['menuR'] = MenuRole::where('menu_id','=',$id)->get();

        // dd($data['menuR']);


        return view('auth.menus.edit',$data);
    }


    public function update(Request $request)
    {
        $id = \base64_decode($request->id);

        $data['role']           = $request->role;

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->menu_id = $request->parent;
        $menu->url = $request->url;
        $menu->icon = $request->icon;
        $menu->order_no = $request->order_no;
        $menu->level = $request->level;
        $menu->updated_at = date('Y-m-d H:i:s');
        $menu->save();

        $MenuRoleD   = MenuRole::where('menu_id','=',$id)->delete();

        foreach ($data['role'] as $key => $value) 
        {
            $menuRole = new \App\Models\Auth\MenuRole();
            $menuRole->role_id = $value;
            $menuRole->menu_id = $menu->id;
            $menuRole->save();
        }

    }



    public function remove($id)
    {
        $id = \base64_decode($id);

        $Menu       = Menu::find($id);
        $Menu->delete();
        $MenuRole   = MenuRole::where('menu_id','=',$id)->delete();


    }
}
