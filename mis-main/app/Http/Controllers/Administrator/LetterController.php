<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Services\Letter;
use App\Models\Auth\Employee;
use Illuminate\Routing\Route;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\Laratrust;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class LetterController extends Controller
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
    public function index($tab_id)
    {
        $laratrust = new Laratrust(app());
        $data['tab_id'] = $tab_id;
        return view('adm.letter.home', $data);
    }   

    public function listLetter_PKS(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getLetter_PKS();

       if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_PKS',['id' => base64_encode($model->id)]).'" type="button" id="uploadPKS" value="" class="btn btn-xs btn-primary" title="Upload File Letter PKS"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/downloadPKS/'.$model->file_letter.'" target="_blank" type="button" id="downloadPKS" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);

    }

    public function MylistLetter_PKS(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getMyLetter_PKS();

        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_PKS',['id' => base64_encode($model->id)]).'" type="button" id="uploadPKS" value="" class="btn btn-xs btn-primary" title="Upload File Letter PKS"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/downloadPKS/'.$model->file_letter.'" target="_blank" type="button" id="downloadPKS" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
                
            }
            $string .= '</div>';
            return
            $string;
                
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);

    }

    public function listLetter_IM(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');

        $queryReqLetter = Letter::getLetter_OPS();

       if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
           
       }

        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_OPS',['id' => base64_encode($model->id)]).'" type="button" id="uploadOPS" class="btn btn-xs btn-primary" title="Upload File Letter OPS"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/downloadOPS/'.$model->file_letter.'" target="_blank" type="button" id="downloadOPS" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function MylistLetter_IM(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getMyLetter_OPS();

        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_OPS',['id' => base64_encode($model->id)]).'" type="button" id="uploadOPS" class="btn btn-xs btn-primary" title="Upload File Letter OPS"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/downloadOPS/'.$model->file_letter.'" target="_blank" type="button" id="downloadOPS" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function listLetter_mrkt(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getLetter_mrkt();

        if ($request->date) {
             $date = $request->date;
             $date = explode('/', $date);
             $date_full = $date[2].'-'.$date[1].'-'.$date[0];
             $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
        }

        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_mrkt',['id' => base64_encode($model->id)]).'" type="button" id="upload_mrkt"class="btn btn-xs btn-primary" title="Upload File Letter Marketing"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_mrkt/'.$model->file_letter.'" target="_blank" type="button" id="download_mrkt" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function MylistLetter_mrkt(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getMyLetter_mrkt();

        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_mrkt',['id' => base64_encode($model->id)]).'" type="button" id="upload_mrkt"class="btn btn-xs btn-primary" title="Upload File Letter Marketing"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_mrkt/'.$model->file_letter.'" target="_blank" type="button" id="download_mrkt" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function listLetter_it(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getLetter_it();

        if ($request->date) {
             $date = $request->date;
             $date = explode('/', $date);
             $date_full = $date[2].'-'.$date[1].'-'.$date[0];
             $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
        }

        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_mrkt',['id' => base64_encode($model->id)]).'" type="button" id="upload_mrkt"class="btn btn-xs btn-primary" title="Upload File Letter Marketing"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_mrkt/'.$model->file_letter.'" target="_blank" type="button" id="download_mrkt" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function MylistLetter_it(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getMyLetter_it();

        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_mrkt',['id' => base64_encode($model->id)]).'" type="button" id="upload_mrkt"class="btn btn-xs btn-primary" title="Upload File Letter Marketing"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_mrkt/'.$model->file_letter.'" target="_blank" type="button" id="download_mrkt" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }


    public function listLetter_hr(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getLetter_hr();

        if ($request->date) {
             $date = $request->date;
             $date = explode('/', $date);
             $date_full = $date[2].'-'.$date[1].'-'.$date[0];
             $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
        }

        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_mrkt',['id' => base64_encode($model->id)]).'" type="button" id="upload_mrkt"class="btn btn-xs btn-primary" title="Upload File Letter Marketing"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_mrkt/'.$model->file_letter.'" target="_blank" type="button" id="download_mrkt" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function MylistLetter_hr(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getMyLetter_hr();

        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_mrkt',['id' => base64_encode($model->id)]).'" type="button" id="upload_mrkt"class="btn btn-xs btn-primary" title="Upload File Letter Marketing"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_mrkt/'.$model->file_letter.'" target="_blank" type="button" id="download_mrkt" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }


    public function listLetter_sales(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');

        $queryReqLetter = Letter::getLetter_sales();
        
        if ($request->date) {
             $date = $request->date;
             $date = explode('/', $date);
             $date_full = $date[2].'-'.$date[1].'-'.$date[0];
             $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
        }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_sales',['id' => base64_encode($model->id)]).'" type="button" id="upload_sales" class="btn btn-xs btn-primary" title="Upload File Letter Sales Confirmation"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_sales/'.$model->file_letter.'" target="_blank" type="button" id="download_sales" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
        
    }
    
    public function MylistLetter_sales(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getMyLetter_sales();
        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_sales',['id' => base64_encode($model->id)]).'" type="button" id="upload_sales" class="btn btn-xs btn-primary" title="Upload File Letter Sales Confirmation"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_sales/'.$model->file_letter.'" target="_blank" type="button" id="download_sales" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);

    }

    public function listLetter_out(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        

        $queryReqLetter = Letter::getLetter_out();

        if ($request->date) {
             $date = $request->date;
             $date = explode('/', $date);
             $date_full = $date[2].'-'.$date[1].'-'.$date[0];
             $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
        }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_out',['id' => base64_encode($model->id)]).'" type="button" id="upload_out" class="btn btn-xs btn-primary" title="Upload File Letter Outgoing Mail"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_out/'.$model->file_letter.'" target="_blank" type="button" id="download_out" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string; 
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function MylistLetter_out(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');
        
        $queryReqLetter = Letter::getMyLetter_out();

        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_out',['id' => base64_encode($model->id)]).'" type="button" id="upload_out" class="btn btn-xs btn-primary" title="Upload File Letter Outgoing Mail"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_out/'.$model->file_letter.'" target="_blank" type="button" id="download_out" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
            }
            $string .= '</div>';
            return
            $string; 
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function listLetter_in(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');

        $queryReqLetter = Letter::getLetter_in();
        
        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_in',['id' => base64_encode($model->id)]).'" type="button" id="upload_in" class="btn btn-xs btn-primary" title="Upload File Letter Incoming Mail"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_in/'.$model->file_letter.'" target="_blank" type="button" id="download_in" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
                
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function MylistLetter_in(Request $request)
    {
        $laratrust = new Laratrust(app());
        $canUpdate = $laratrust->can('update-menu');
        $canDelete = $laratrust->can('delete-menu');

        $queryReqLetter = Letter::getLetter_in();
        
        if ($request->date) {
            $date = $request->date;
            $date = explode('/', $date);
            $date_full = $date[2].'-'.$date[1].'-'.$date[0];
            $queryReqLetter->whereRaw('datediff(day, created_at, ?) = 0', [$date_full]);
       }
        $laratrust->user()->roles;
        return Datatables::of($queryReqLetter)
        ->editColumn('created_by', function ($model) {
            $empl = Employee::find($model->created_by);
            return $empl->name;
        })
        ->addColumn('action', function ($model) {

            $string = '<div class="btn-group">';
            {
                $string .='<a href="'.route('adm.upload_in',['id' => base64_encode($model->id)]).'" type="button" id="upload_in" class="btn btn-xs btn-primary" title="Upload File Letter Incoming Mail"><i class="fa fa-upload"></i></a>';
            }
            {
                if($model->file_letter!= null)
                {
                    $string .='&nbsp;&nbsp;&nbsp;<a href="/adm/download_in/'.$model->file_letter.'" target="_blank" type="button" id="download_in" class="btn btn-xs btn-success" title="Download File Letter"><i class="fa fa-download"></i></a>';
                }
                
            }
            $string .= '</div>';
            return
            $string;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function create_pks ()
    {
        $this->middleware('permission:create-users');
        $type = 'new';
        $numb = 0;
        $nomor_surat_pks=$this->getLetterNumber_pks($type,$numb);
        return view('adm.letter.create_pks',compact('nomor_surat_pks'));
    }

    public function create_ops ()
    {
        $this->middleware('permission:create-users');
        
        $nomor_surat_ops=$this->getLetterNumber_ops();
        return view('adm.letter.create_ops',compact('nomor_surat_ops'));
    }

    public function create_mrkt ()
    {
        $this->middleware('permission:create-users');

        $nomor_surat_mrkt=$this->getLetterNumber_mrkt();
        return view('adm.letter.create_mrkt',compact('nomor_surat_mrkt'));
    }

    public function create_it ()
    {
        $this->middleware('permission:create-users');

        $nomor_surat_it=$this->getLetterNumber_it();
        return view('adm.letter.create_it',compact('nomor_surat_it'));
    }

    public function create_hr ()
    {
        $this->middleware('permission:create-users');

        $nomor_surat_hr=$this->getLetterNumber_hr();
        return view('adm.letter.create_hr',compact('nomor_surat_hr'));
    }

    public function create_sales ()
    {
        $this->middleware('permission:create-users');

        $nomor_surat_sales=$this->getLetterNumber_sales();
        return view('adm.letter.create_sales',compact('nomor_surat_sales'));
    }

    public function create_out ()
    {
        $this->middleware('permission:create-users');

        $nomor_surat_out=$this->getLetterNumber_out();
        $incoming_mail=$this->getClientLetterNumber();
        return view('adm.letter.create_out',compact('nomor_surat_out','incoming_mail'));
    }

    public function create_in ()
    {
        $this->middleware('permission:create-users');

        return view('adm.letter.create_in');
    }

    public function store_pks(Request $request)
    {
        $this->validate($request, [
            'purpose' => 'required',
            'subject' => 'required',
            'from_pks' => 'required',
        ]);
        
        $type = $request->type;
        $numb = $request->add_num;

        $data['type_letter_cd']  = 'LEG';
        $data['letter_number']   = $this->getLetterNumber_pks($type,$numb);
        $data['purpose']         = $request->purpose;
        $data['subject']         = $request->subject;
        $data['from']            = $request->from_pks;
        $data['created_by']      = Auth::user()->uuid;
        $data['created_at']      = date('Y-m-d H:i:s');
        $data['updated_at']      = date('Y-m-d H:i:s');

        Letter::insert_pks($data);
    }

    public function store_ops(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'from_ops' => 'required',
            'to_ops' => 'required',
        ]);

        $data['type_letter_cd']  = 'MEMO-OPS';
        $data['letter_number']   = $this->getLetterNumber_ops();
        $data['subject']         = $request->subject;
        $data['from']            = $request->from_ops;
        $data['to']              = $request->to_ops;
        $data['created_by']      = Auth::user()->uuid;
        $data['created_at']      = date('Y-m-d H:i:s');
        $data['updated_at']      = date('Y-m-d H:i:s');

        Letter::insert_ops($data);

    }

    public function store_mrkt(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'from_mrkt' => 'required',
            'to_mrkt' => 'required',
        ]);
        
        $data['type_letter_cd']  = 'MEMO-SM';
        $data['letter_number']   = $this->getLetterNumber_mrkt();
        $data['subject']         = $request->subject;
        $data['from']            = $request->from_mrkt;
        $data['to']              = $request->to_mrkt;
        $data['created_by']      = Auth::user()->uuid;
        $data['created_at']      = date('Y-m-d H:i:s');
        $data['updated_at']      = date('Y-m-d H:i:s');

        Letter::insert_mrkt($data);

    }

    public function store_it(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'from_it' => 'required',
            'to_it' => 'required',
        ]);
        
        $data['type_letter_cd']  = 'MEMO-IT';
        $data['letter_number']   = $this->getLetterNumber_it();
        $data['subject']         = $request->subject;
        $data['from']            = $request->from_it;
        $data['to']              = $request->to_it;
        $data['created_by']      = Auth::user()->uuid;
        $data['created_at']      = date('Y-m-d H:i:s');
        $data['updated_at']      = date('Y-m-d H:i:s');

        Letter::insert_it($data);

    }

    public function store_hr(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'from_hr' => 'required',
            'to_hr' => 'required',
        ]);
        
        $data['type_letter_cd']  = 'MEMO-HR';
        $data['letter_number']   = $this->getLetterNumber_hr();
        $data['subject']         = $request->subject;
        $data['from']            = $request->from_hr;
        $data['to']              = $request->to_hr;
        $data['created_by']      = Auth::user()->uuid;
        $data['created_at']      = date('Y-m-d H:i:s');
        $data['updated_at']      = date('Y-m-d H:i:s');

        Letter::insert_hr($data);

    }

    public function store_sales(Request $request)
    {
        $this->validate($request, [
            'purpose' => 'required',
            'note' => 'required',
        ]);
        
        $data['type_letter_cd']  = 'SC';
        $data['letter_number']   = $this->getLetterNumber_sales();
        $data['purpose']         = $request->purpose;
        $data['note']            = $request->note;
        $data['created_by']      = Auth::user()->uuid;
        $data['created_at']      = date('Y-m-d H:i:s');
        $data['updated_at']      = date('Y-m-d H:i:s');

        Letter::insert_sales($data);

    }

    public function store_out(Request $request)
    {
        $this->validate($request, [
            'incoming_mail' => 'required',
            'attention' => 'required',
            'company' => 'required',
            'subject' => 'required',
            'from' => 'required',
            'note' => 'required',
        ]);
        
        $data['out']['type_letter_cd']  = 'OUT';
        $data['out']['letter_number']   = $this->getLetterNumber_out();
        $data['in']['incoming_mail']    = $request->incoming_mail;
        $data['out']['attention_to']    = $request->attention;
        $data['out']['company']         = $request->company;
        $data['out']['subject']         = $request->subject;
        $data['out']['from']            = $request->from;
        $data['out']['note']            = $request->note;
        $data['out']['created_by']      = Auth::user()->uuid;
        $data['out']['created_at']      = date('Y-m-d H:i:s');
        $data['out']['updated_at']      = date('Y-m-d H:i:s');

        Letter::insert_out($data);

    }

    public function store_in(Request $request)
    {
        $this->validate($request, [
            'client_ltr_nmbr' => 'required',
            'attention' => 'required',
            'company' => 'required',
            'subject' => 'required',
            'note' => 'required',
        ]);   
        
        $data['client_letter_number']   = $request->client_ltr_nmbr;
        $data['attention_to']           = $request->attention;
        $data['company']                = $request->company;
        $data['subject']                = $request->subject;
        $data['note']                   = $request->note;
        $data['created_by']             = Auth::user()->uuid;
        $data['created_at']             = date('Y-m-d H:i:s');
        $data['updated_at']             = date('Y-m-d H:i:s');

        Letter::insert_in($data);

    }

    public function getLetterNumber_pks($type,$numb){
    
        $bulan = date('n');
        $romawi =$this->Romawi($bulan);
        $tahun = date ('Y');
        $nomor = Letter::get_last_pks_no(); 
        if ($type == 'new') {
            $code = 'LEG/JKT-MBPS';
        }
        else {
            $code = 'LEG/JKT-MBPS/ADD-'.$this->kdRomawi($numb);
        }

        $last_no =(count($nomor)>0) ? $nomor[0]->letter_number : "S"."000/".$code."/".$romawi."/".$tahun;
        $check_first_char = substr($last_no, 0, 1);
        $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 1);
        $explode =explode('/',$last_no);

        if ($tahun > $nomor[0]->year) {

            $explode[0] = 0;
        }
        
        $nomor_surat_pks= "S".sprintf('%03d',$explode[0]+1)."/".$code."/".$romawi."/".$tahun;
        return $nomor_surat_pks;

    }

    public function getLetterNumber_ops(){
    
        // $bulan = date('n');
        // $romawi =$this->Romawi($bulan);
        // $tahun = date ('Y');
        // $nomor = Letter::get_last_ops_no();
        // $last_no =(count($nomor)>0) ? $nomor[0]->letter_number : "Memo No."."000/IM/MBPS/".$romawi."/".$tahun;
        // $check_first_char = substr($last_no, 0, 1);
        // $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 8);
        // $explode =explode('/',$last_no);
        // if ($bulan > $nomor[0]->month){
        //     $explode[0] = 0;
        // }
        
        // $nomor_surat_ops= "Memo No.".sprintf('%03d',$explode[0]+1)."/IM/MBPS/".$romawi."/".$tahun;
        // return $nomor_surat_ops;
        
        $bulan = date('n');
        $romawi =$this->Romawi($bulan);
        $tahun = date ('Y');
        $nomor = Letter::get_last_ops_no();
        $last_no =(count($nomor)>0) ? $nomor[0]->letter_number : "Memo No."."000/IM/MBPS/".$romawi."/".$tahun;
        $check_first_char = substr($last_no, 0, 1);
        $check_format_char = substr($last_no, 0, 8);
        if ($check_format_char != "Memo No.") {
            $last_no = "Memo No."."000/IM/MBPS/".$romawi."/".$tahun; 
        } else {
            $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 8);
        }
        $explode =explode('/',$last_no);
        $explode[0] = ($check_format_char != "Memo No.") ? substr($last_no, 8, 3) : $explode[0];
        if ($bulan > $nomor[0]->month){
            $explode[0] = 0;
        }
        
        $nomor_surat_ops= "Memo No.".sprintf('%03d',$explode[0]+1)."/IM/MBPS/".$romawi."/".$tahun;
        return $nomor_surat_ops;

    }

    public function getLetterNumber_mrkt(){
    
        $bulan = date('n');
        $romawi =$this->Romawi($bulan);
        $tahun = date ('Y');
        $nomor = Letter::get_last_mrkt_no();
        $last_no = (count($nomor)>0) ? $nomor[0]->letter_number : "S"."000/SM/MBPS/".$romawi."/".$tahun;
        $check_first_char = substr($last_no, 0, 1);
        $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 1);

        $explode = explode('/',$last_no);
        if ($tahun > $nomor[0]->year) {
            $explode[0] = 0;
        }
        
        $nomor_surat_mrkt= "S".sprintf('%03d',$explode[0]+1)."/SM/MBPS/".$romawi."/".$tahun;
        return $nomor_surat_mrkt;

    }

    public function getLetterNumber_it(){
    
        $bulan = date('n');
        $romawi =$this->Romawi($bulan);
        $tahun = date ('Y');
        $nomor = Letter::get_last_it_no();
        $last_no = (count($nomor)>0) ? $nomor[0]->letter_number : "S"."000/IT/MBPS/".$romawi."/".$tahun;
        $check_first_char = substr($last_no, 0, 1);
        $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 1);
        $explode = explode('/',$last_no);
        
        $nomor_surat_it= "S".sprintf('%03d',$explode[0]+1)."/IT/MBPS/".$romawi."/".$tahun;
        return $nomor_surat_it;

    }

    public function getLetterNumber_hr(){
    
        $bulan = date('n');
        $romawi =$this->Romawi($bulan);
        $tahun = date ('Y');
        $nomor = Letter::get_last_hr_no();
        $last_no = (count($nomor)>0) ? $nomor[0]->letter_number : "S"."000/HR/MBPS/".$romawi."/".$tahun;
        $check_first_char = substr($last_no, 0, 1);
        $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 1);
        $explode = explode('/',$last_no);
        
        $nomor_surat_hr= "S".sprintf('%03d',$explode[0]+1)."/HR/MBPS/".$romawi."/".$tahun;
        return $nomor_surat_hr;

    }

    public function getLetterNumber_sales(){
    
        $bulan = date('n');
        $romawi =$this->Romawi($bulan);
        $tahun = date ('Y');
        $nomor = Letter::get_last_sales_no();
        $last_no =(count($nomor)>0) ? $nomor[0]->letter_number : "S"."S000/SC/".$romawi."/".$tahun;
        $check_first_char = substr($last_no, 0, 1);
        $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 1);

        $explode =explode('/',$last_no);

        if ($tahun > $nomor[0]->year) {

            $explode[0] = 0;
        }
        
        $nomor_surat_sales= "S".sprintf('%03d',$explode[0]+1)."/SC/".$romawi."/".$tahun;
        return $nomor_surat_sales;

    }

    public function getLetterNumber_out(){
    
        $bulan = date('n');
        $romawi =$this->Romawi($bulan);
        $tahun = date ('Y');
        $nomor = Letter::get_last_out_no();
        $last_no =(count($nomor)>0) ? $nomor[0]->letter_number : "S"."000/OUT/".$romawi."/".$tahun;
        $check_first_char = substr($last_no, 0, 1);
        $last_no = (is_numeric($check_first_char)) ? $last_no : substr($last_no, 1);

        $explode =explode('/',$last_no);

        if ($tahun > $nomor[0]->year) {

            $explode[0] = 0;
        }
        
        $nomor_surat_out= "S".sprintf('%03d',$explode[0]+1)."/OUT/".$romawi."/".$tahun;
        return $nomor_surat_out;

    }

    public function getClientLetterNumber(){

        $incoming_mail = Letter::get_client_letter_number();
        return $incoming_mail;
    }


    public function upload_PKS($id)
    {
        $this->middleware('permission:update-menu');

        $id = \base64_decode($id);

        $data['req_letter_number_pks'] = Letter::getReqLetterPKS($id);

		return view ('adm.letter.upload_PKS',$data);
	}
 
    public function store_upload_PKS(Request $request)
    {
        $this->validate($request, [
            'file'       => 'required',
        ]);

        $id = $request->id;
        $request->hasFile('file'); 
        $files = $request->file('file');
        $hashName = $files->hashName();
        $folderName = 'UploadLetter/LetterPKS';
        $fileName = $hashName . '.' . $files->getClientOriginalExtension();
            
                $users = Letter::getReqLetterPKS($id);
                $usersFile = storage_path().'/app/UploadLetter/LetterPKS/'.$users->file_letter;
                if (file_exists($usersFile)) { 
                    File::delete($usersFile);
                } 
        
        $files->store($folderName);
        Storage::move($folderName . '/' . $hashName, $folderName . '/' . $fileName);
        $data['file_letter']        = $fileName;
        Letter::update_pks($data,$id);

        return redirect()->route('adm.letter.index', ['tab_id' => 'alldoc']);
         
    }
    
    public function downloadPKS($fileName)
    {
        $path = storage_path().'/app/UploadLetter/LetterPKS/'.$fileName;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }
    
    public function upload_OPS($id)
    {
        $this->middleware('permission:update-menu');

       $id = \base64_decode($id);

       $data['req_letter_number_memo_ops'] = Letter::getReqLetterOPS($id);

       return view ('adm.letter.upload_OPS',$data);
    }

    public function store_upload_OPS(Request $request)
    {
       $this->validate($request, [
           'file'       => 'required',
       ]);
       $id = $request->id;
       $request->hasFile('file'); 
       $files = $request->file('file');
       $hashName = $files->hashName();
       $folderName = 'UploadLetter/LetterOPS';
       $fileName = $hashName . '.' . $files->getClientOriginalExtension();
           
               $users = Letter::getReqLetterOPS($id);
               $usersFile = storage_path().'/app/UploadLetter/LetterOPS/'.$users->file_letter;
               if (file_exists($usersFile)) { 
                   File::delete($usersFile);
               } 
       
        $files->store($folderName);
        Storage::move($folderName . '/' . $hashName, $folderName . '/' . $fileName);
        $data['file_letter']        = $fileName;
        Letter::update_ops($data,$id);

        return redirect()->route('adm.letter.index', ['tab_id' => 'alldoc']);
    }

    public function downloadOPS($fileName)
    {
        $path = storage_path().'/app/UploadLetter/LetterOPS/'.$fileName;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    public function upload_mrkt($id)
    {
        $this->middleware('permission:update-menu');

        $id = \base64_decode($id);

        $data['req_letter_number_memo_mrkt'] = Letter::getReqLetter_mrkt($id);

        return view ('adm.letter.upload_mrkt',$data);
    }

    public function store_upload_mrkt(Request $request)
    {
        $this->validate($request, [
            'file'       => 'required',
        ]);
        $id = $request->id;
        $request->hasFile('file'); 
        $files = $request->file('file');
        $hashName = $files->hashName();
        $folderName = 'UploadLetter/LetterMarketing';
        $fileName = $hashName . '.' . $files->getClientOriginalExtension();
            
                $users = Letter::getReqLetter_mrkt($id);
                $usersFile = storage_path().'/app/UploadLetter/LetterMarketing/'.$users->file_letter;
                if (file_exists($usersFile)) { 
                    File::delete($usersFile);
                } 
        
        $files->store($folderName);
        Storage::move($folderName . '/' . $hashName, $folderName . '/' . $fileName);
        $data['file_letter']        = $fileName;
        Letter::update_mrkt($data,$id);
 
        return redirect()->route('adm.letter.index', ['tab_id' => 'alldoc']);
    }

    public function download_mrkt($fileName)
    {
        $path = storage_path().'/app/UploadLetter/LetterMarketing/'.$fileName;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    public function upload_sales($id)
    {
        $this->middleware('permission:update-menu');

        $id = \base64_decode($id);

        $data['req_letter_number_sales'] = Letter::getReqLetter_sales($id);

        return view ('adm.letter.upload_sales',$data);
    }

    public function store_upload_sales(Request $request)
    {
        $this->validate($request, [
            'file'       => 'required',
        ]);
        $id = $request->id;
        $request->hasFile('file'); 
        $files = $request->file('file');
        $hashName = $files->hashName();
        $folderName = 'UploadLetter/LetterSales';
        $fileName = $hashName . '.' . $files->getClientOriginalExtension();
            
                $users = Letter::getReqLetter_sales($id);
                $usersFile = storage_path().'/app/UploadLetter/LetterSales/'.$users->file_letter;
                if (file_exists($usersFile)) { 
                    File::delete($usersFile);
                } 
        
        $files->store($folderName);
        Storage::move($folderName . '/' . $hashName, $folderName . '/' . $fileName);
        $data['file_letter']        = $fileName;
        Letter::update_sales($data,$id);
 
        return redirect()->route('adm.letter.index', ['tab_id' => 'alldoc']);
    }

    public function download_sales($fileName)
    {
        $path = storage_path().'/app/UploadLetter/LetterSales/'.$fileName;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    public function upload_out($id)
    {
        $this->middleware('permission:update-menu');

        $id = \base64_decode($id);

        $data['req_ougoing_mail'] = Letter::getReqLetter_out($id);

        return view ('adm.letter.upload_out',$data);
    }

    public function store_upload_out(Request $request)
    {
        $this->validate($request, [
            'file'       => 'required',
        ]);

        $id = $request->id;
        $request->hasFile('file'); 
        $files = $request->file('file');
        $hashName = $files->hashName();
        $folderName = 'UploadLetter/LetterOutgoing';
        $fileName = $hashName . '.' . $files->getClientOriginalExtension();
            
                $users = Letter::getReqLetter_out($id);
                $usersFile = storage_path().'/app/UploadLetter/LetterOutgoing/'.$users->file_letter;
                if (file_exists($usersFile)) { 
                    File::delete($usersFile);
                } 
        
        $files->store($folderName);
        Storage::move($folderName . '/' . $hashName, $folderName . '/' . $fileName);
        $data['file_letter']        = $fileName;
        Letter::update_out($data,$id);

        return redirect()->route('adm.letter.index', ['tab_id' => 'alldoc']);
    }

    public function download_out($fileName)
    {
        $path = storage_path().'/app/UploadLetter/LetterOutgoing/'.$fileName;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    public function upload_in($id)
    {
        $this->middleware('permission:update-menu');

        $id = \base64_decode($id);

        $data['incoming_mail'] = Letter::getReqLetter_in($id);

        return view ('adm.letter.upload_in',$data);
    }

    public function store_upload_in(Request $request)
    {
        $this->validate($request, [
            'file'       => 'required',
        ]);

        $id = $request->id;
        $request->hasFile('file'); 
        $files = $request->file('file');
        $hashName = $files->hashName();
        $folderName = 'UploadLetter/LetterIncoming';
        $fileName = $hashName . '.' . $files->getClientOriginalExtension();
            
                $users = Letter::getReqLetter_in($id);
                $usersFile = storage_path().'/app/UploadLetter/LetterIncoming/'.$users->file_letter;
                if (file_exists($usersFile)) { 
                    File::delete($usersFile);
                } 
        
        $files->store($folderName);
        Storage::move($folderName . '/' . $hashName, $folderName . '/' . $fileName);
        $data['file_letter']        = $fileName;
        Letter::update_in($data,$id);
 
        return redirect()->route('adm.letter.index', ['tab_id' => 'alldoc']);
        
    }

    public function download_in($fileName)
    {
        $path = storage_path().'/app/UploadLetter/LetterIncoming/'.$fileName;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    public function Romawi($bln){
        switch ($bln){
        case 1: 
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }}

    function kdRomawi($angka)
    {
        $hsl = "";
        if ($angka < 1 || $angka > 5000) { 
            // Statement di atas buat nentuin angka ngga boleh dibawah 1 atau di atas 5000
            $hsl = "";
        } else {
            while ($angka >= 1000) {
                // While itu termasuk kedalam statement perulangan
                // Jadi misal variable angka lebih dari sama dengan 1000
                // Kondisi ini akan di jalankan
                $hsl .= "M"; 
                // jadi pas di jalanin , kondisi ini akan menambahkan M ke dalam
                // Varible hsl
                $angka -= 1000;
                // Lalu setelah itu varible angka di kurangi 1000 ,
                // Kenapa di kurangi
                // Karena statment ini mengambil 1000 untuk di konversi menjadi M
            }
        }
    
    
        if ($angka >= 500) {
            // statement di atas akan bernilai true / benar
            // Jika var angka lebih dari sama dengan 500
            if ($angka > 500) {
                if ($angka >= 900) {
                    $hsl .= "CM";
                    $angka -= 900;
                } else {
                    $hsl .= "D";
                    $angka-=500;
                }
            }
        }
        while ($angka>=100) {
            if ($angka>=400) {
                $hsl .= "CD";
                $angka -= 400;
            } else {
                $angka -= 100;
            }
        }
        if ($angka>=50) {
            if ($angka>=90) {
                $hsl .= "XC";
                $angka -= 90;
            } else {
                $hsl .= "L";
                $angka-=50;
            }
        }
        while ($angka >= 10) {
            if ($angka >= 40) {
                $hsl .= "XL";
                $angka -= 40;
            } else {
                $hsl .= "X";
                $angka -= 10;
            }
        }
        if ($angka >= 5) {
            if ($angka == 9) {
                $hsl .= "IX";
                $angka-=9;
            } else {
                $hsl .= "V";
                $angka -= 5;
            }
        }
        while ($angka >= 1) {
            if ($angka == 4) {
                $hsl .= "IV"; 
                $angka -= 4;
            } else {
                $hsl .= "I";
                $angka -= 1;
            }
        }
    
        return ($hsl);
    }

    
}