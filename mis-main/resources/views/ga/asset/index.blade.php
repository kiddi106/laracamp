@extends('layouts.app')
@push('css')
{{-- dateRangepicker --}}
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">



{{-- lightbox --}}
<link rel="stylesheet" href="{{ asset('/admin/plugins/ekko-lightbox/ekko-lightbox.css') }}">

<link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" rel="stylesheet">

@endpush

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    {{-- <div class="container"> --}}
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Asset</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Asset</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    {{-- </div><!-- /.container-fluid --> --}}
</div>
<!-- /.content-header -->
<div class="row">
    <div class="col-2">
        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <div class="raw">
                    <div class="card-header">
                        <h5 class="float-left">Search <i class="fas fa-search"> </i></h5>
                        <a href="{{ route('ga.asset.create') }}" class="btn btn-sm btn-primary float-right"><i
                                class="fa fa-plus"></i> Add Asset</a>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{!! route('ga.asset.index') !!}" method="GET" id="frm">
                            <input type="hidden" name="rowPerPage" id="rowPerPage" value="{{ $paging['rowPerPage'] }}">
                            <input type="hidden" name="pageNumber" id="pageNumber" value="{{ $paging['pageNumber'] }}">
                            @csrf
                            <div class="form-group row">
                                <label for="asset_no" class="col-md-8 col-form-label text-md-left">Asset
                                    Number</label>
                                <input id="asset_no" type="text" class="form-control" name="asset_no"
                                    autocomplete="asset_no" value="{{ $search['asset_no'] }}" autofocus
                                    placeholder="Asset Number">
                            </div>
                            <div class="form-group row">
                                <label for="serial_no" class="col-md-8 col-form-label text-md-left">Serial
                                    Number</label>

                                <input id="serial_no" type="text" class="form-control" name="serial_no"
                                    value="{{ $search['serial_no'] }}" autofocus placeholder="Serial Number">
                            </div>
                            <div class="form-group row">
                                <label for="Category" class="col-md-8 col-form-label text-md-left">Category</label>

                                <select name="category_id" id="category"
                                    class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="">---Category---</option>
                                    @foreach (App\Models\Mst\CategoryAsset::All() as $category)
                                    <option value="{{ $category->category_id }}" @if($search['category_id'] !=null &&
                                        $search['category_id']==$category->category_id) SELECTED
                                        @endif>{{ $category->category_nm }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="Status" class="col-md-8 col-form-label text-md-left">Status</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="">---Status---</option>
                                    @foreach (App\Services\Asset::getStatus()->whereNotNull('parent_id')
                                    ->orWhere('status_id','=','6')->get() as
                                    $status)
                                    <option value="{{ $status->status_id }}" @if($search['status'] !=null &&
                                        $search['status']==$status->status_id) SELECTED
                                        @endif>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="brand_id" class="col-md-8 col-form-label text-md-left">Brand</label>
                                <select name="brand_id" id="brand_id"
                                    class="form-control @error('brand_id') is-invalid @enderror">
                                    <option value="">---Brand---</option>
                                    @foreach (App\Models\Mst\BrandAsset::All() as $brand)
                                    <option value="{{ $brand->brand_id }}" @if($search['brand_id'] !=null &&
                                        $search['brand_id']==$brand->brand_id) SELECTED
                                        @endif>{{ $brand->brand_nm }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="model_id" class="col-md-8 col-form-label text-md-left">Model</label>
                                <select name="model_id" id="model_id"
                                    class="form-control @error('model_id') is-invalid @enderror">
                                    <option value="">---Model Type---</option>
                                    @foreach (App\Models\Mst\ModelTypeAsset::All() as $model)
                                    <option value="{{ $model->model_type_id }}" @if($search['model_id'] !=null &&
                                        $search['model_id']==$model->model_type_id) SELECTED
                                        @endif>{{ $model->model_type_nm }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 control-label">Arrived :
                                </label>

                                <button type="button" class="btn btn-info pull-right" id="daterange_arrived-btn">
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        @if ($search['arrived_dt'] != ' - ')
                                        {{$search['arrived_dt']}}
                                        @else
                                        Date Range Picker
                                        @endif
                                    </span>
                                    <i class="fa fa-caret-down"></i>
                                    <input type="hidden" id="daterangestart_arrived" name="daterangestart_arrived"
                                        value="">
                                    <input type="hidden" id="daterangeend_arrived" name="daterangeend_arrived" value="">
                                </button>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 control-label">Handover :
                                </label>

                                <button type="button" class="btn btn-info pull-right" id="daterange_handover-btn">
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        @if ($search['handover_dt'] != ' - ')
                                        {{$search['handover_dt']}}
                                        @else
                                        Date Range Picker
                                        @endif
                                    </span>
                                    <i class="fa fa-caret-down"></i>
                                    <input type="hidden" id="daterangestart_handover" name="daterangestart_handover"
                                        value="">
                                    <input type="hidden" id="daterangeend_handover" name="daterangeend_handover"
                                        value="">
                                </button>
                            </div>
                            <button type="submit" class="btn btn-sm bg-green float-right">
                                Search
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-10">
        @php
        $displayPageMenu = [10, 25, 50, 75, 100];
        @endphp
        <div>
            <div class="form-group inline">
                <div class="row">
                    <div class="col-sm-1">
                        <label for="_rowPerPage" class="float-right">Show:</label>
                    </div>
                    <div class="col-sm-1">
                        <select id="_rowPerPage" class="form-control input-normal">
                            @foreach ($displayPageMenu as $item)
                            @if ($item != $paging['rowPerPage'])
                            <option value="{{ $item }}">{{ $item }}</option>
                            @else
                            <option value="{{ $item }}" selected>{{ $item }}</option>
                            @endif
                            @endforeach
                        </select></div>
                    <div class="col-sm-2">
                        <label for="_rowPerPage">per pages (Total Data: {{ $paging['totalData'] }})</label>
                    </div>
                    <div class="col-sm-8">
                        <ul class="pagination float-right" style="margin: 0 0 !important;">
                            @if ($paging['totalPage'] == 1)
                            <li class="page-item active"><a class="badge-primary page-link" href="#"
                                    onclick="paging(1)">1</a></li>
                            @else
                            @if ($paging['totalPage'] <= 5) @for($i=1; $i <=$paging['totalPage']; $i++) <li
                                @if($i==$paging['pageNumber']) class="page-item active" @endif><a class=" page-link"
                                    href="#" onclick="paging({{ $i }})">{{ $i }}</a>
                                </li>
                                @endfor
                                @else
                                @if ($paging['pageNumber'] > 1)
                                <li class="page-item"><a class="page-link " href="#"
                                        onclick="paging({{ $paging['pageNumber'] - 1 }})">Previous</a></li>
                                @endif
                                <li @if(1==$paging['pageNumber']) class="page-item active" @endif><a class=" page-link"
                                        href="#" onclick="paging(1)">1</a>
                                </li>
                                @if ($paging['pageNumber'] < 5 && $paging['totalPage']>= 5)
                                    @for($i = 2; $i <= 5; $i++) <li @if($i==$paging['pageNumber'])
                                        class="active page-item" @else class="page-item" @endif><a class="page-link "
                                            href="#" onclick="paging({{ $i }})">{{ $i }}</a></li>
                                        @endfor
                                        <li class="disabled page-item"><a class="page-link " href="#">...</a>
                                        </li>
                                        <li><a class="page-link " href="#"
                                                onclick="paging({{ $paging['totalPage'] }})">{{ $paging['totalPage'] }}</a>
                                        </li>
                                        @elseif ($paging['totalPage'] - $paging['pageNumber'] < 5) <li
                                            class="page-item">
                                            <a class="page-link " href="#">...</a></li>
                                            @for($i = $paging['totalPage'] - 5; $i <= $paging['totalPage']; $i++) <li
                                                @if($i==$paging['pageNumber']) class="active page-item" @else
                                                class="page-item" @endif><a class="page-link " href="#"
                                                    onclick="paging({{ $i }})">{{ $i }}</a></li>
                                                @endfor
                                                @else
                                                <li class="disabled page-item"><a class="page-link " href="#">...</a>
                                                </li>
                                                <li><a class="page-link " href="#"
                                                        onclick="paging({{ $paging['pageNumber'] - 1 }})">{{ $paging['pageNumber'] - 1 }}</a>
                                                </li>
                                                <li class="active page-item"><a class="page-link "
                                                        href="#">{{ $paging['pageNumber'] }}</a></li>
                                                @if ($paging['pageNumber'] + 1 <= $paging['totalPage']) <li><a
                                                        class="page-link " href="#"
                                                        onclick="paging({{ $paging['pageNumber'] + 1 }})">{{ $paging['pageNumber'] + 1 }}</a>
                                                    </li>
                                                    <li class="disabled page-item"><a class="page-link "
                                                            href="#">...</a></li>
                                                    <li class="page-item"><a class="page-link " href="#"
                                                            onclick="paging({{ $paging['totalPage'] }})">{{ $paging['totalPage'] }}</a>
                                                    </li>
                                                    @endif
                                                    @endif
                                                    @if ($paging['pageNumber'] < $paging['totalPage']) <li><a
                                                            class="page-link " href="#"
                                                            onclick="paging({{ $paging['pageNumber'] + 1 }})">Next</a>
                                                        </li>
                                                        @endif
                                                        @endif
                                                        @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-info">
            <div class="card-header with-border">
                <h4 class="card-title">Asset Field</h4>
                <div class="card-tools">

                </div>
            </div>
            <div class="card-body" id="asset">
                @if (count($assets)===0)
                No Result
            </div>
            @endif
            <div class="card-body" id="asset">
                @foreach ($assets as $Asset)
                <div class="card card-primary card-outline">
                    <div class="card-header text-muted">
                        <h4 class="card-title">Asset</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="raw">
                            <h5><b>{{$Asset->brand_nm . " - " . $Asset->model_type_nm}}</b></h5>
                        </div>
                        <div class="row">
                            {{-- @php
                                $link_image = link_to_asset("{{ asset('/img/ga/asset/'.$Asset->file_image_nm)}}");
                                if (!file_axists($link)) {
                                    $link_image = "{{ asset('/img/noimage.png')}}";
                                }
                            @endphp --}}
                            <div class="col-3">
                                <a href="{{ asset('/img/ga/asset/'.$Asset->file_image_nm)}}" data-toggle="lightbox"
                                    data-gallery="example-gallery"
                                    data-title="{{$Asset->brand_nm . " - " . $Asset->model_type_nm}}">
                                    <img src="{{ asset('/img/ga/asset/'.$Asset->file_image_nm)}}"
                                        onerror="this.src='{{ asset('/img/noimage.png')}}'" class="img-fluid mb-3"
                                        alt="black sample" style="width:50%; height:150px">
                                </a>
                            </div>
                            <div class="col-3">
                                <ul class="fa-ul text">
                                    <li class=""><span class="fa-li"><i
                                                class="fa fa-fw fa-book"></i></span>{{$Asset->category_nm}}
                                    </li>
                                    <br>
                                    <li class=""><span class="fa-li"><i class="fa fa-fw fa-check-square"></i></span>
                                        {{$Asset->asset_no}}</li>
                                    <br>
                                    <li class=""><span class="fa-li"><i class="fa fa-fw  fa-user"></i></span>
                                        {{$Asset->serial_no}}</li>
                                </ul>
                            </div>
                            <div class="col-4">
                                <ul class="fa-ul text">
                                    <li class=""><span class="fa-li"><i class="fa fa-fw fa-book"></i></span>Detail
                                    </li>
                                    <br>
                                    <li class=""><span class="fa-li"><i class="fa fa-fw fa-check-square"></i></span>
                                        {{$Asset->status_nm}}</li>
                                    <br>
                                    <li class=""><span class="fa-li"><i class="fa fa-fw  fa-user"></i></span>
                                        {{$Asset->checkout_to_nm .' - '. $Asset->department_nm}}</li>
                                </ul>
                            </div>
                            <div class="col-2">
                                <ul class="fa-ul text-muted">
                                    <li><a href="{{ route('ga.asset.show',['id' => $Asset->id]) }}" target="_blank"
                                            type="button" class="btn btn-sm btn-success">Detail</a></li>
                                    <br>
                                    <li><a href="{{ route('ga.asset.edit',['id' => $Asset->id]) }}" type="button"
                                            class="btn btn-sm btn-success">Edit</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <br>
                <div class="card-footer">
                    @if (count($assets)===0)
                    <label>Showing 0 To 0 From 0 Data</label>
                    @else
                    <label>Showing {{$paging['offset'] + 1}} To {{$paging['showData'] + $paging['offset']}} From {{$paging['totalData']}} Data</label>
                    @endif
                

                    <ul class="pagination float-right" style="margin: 0 0 !important;">
                        @if ($paging['totalPage'] == 1)
                        <li class="page-item active"><a class="badge-primary page-link" href="#"
                                onclick="paging(1)">1</a></li>
                        @else
                        @if ($paging['totalPage'] <= 5) @for($i=1; $i <=$paging['totalPage']; $i++) <li
                            @if($i==$paging['pageNumber']) class="page-item active" @else class="page-item" @endif><a
                                class=" page-link" href="#" onclick="paging({{ $i }})">{{ $i }}</a>
                            </li>
                            @endfor
                            @else
                            @if ($paging['pageNumber'] > 1)
                            <li class="page-item"><a class="page-link " href="#"
                                    onclick="paging({{ $paging['pageNumber'] - 1 }})">Previous</a></li>
                            @endif
                            <li @if(1==$paging['pageNumber']) class="page-item active" @endif><a class=" page-link"
                                    href="#" onclick="paging(1)">1</a>
                            </li>
                            @if ($paging['pageNumber'] < 5 && $paging['totalPage']>= 5)
                                @for($i = 2; $i <= 5; $i++) <li @if($i==$paging['pageNumber']) class="active page-item"
                                    @else class="page-item" @endif><a class="page-link " href="#"
                                        onclick="paging({{ $i }})">{{ $i }}</a></li>
                                    @endfor
                                    <li class="disabled page-item"><a class="page-link " href="#">...</a>
                                    </li>
                                    <li><a class="page-link " href="#"
                                            onclick="paging({{ $paging['totalPage'] }})">{{ $paging['totalPage'] }}</a>
                                    </li>
                                    @elseif ($paging['totalPage'] - $paging['pageNumber'] < 5) <li class="page-item">
                                        <a class="page-link" href="#">...</a></li>
                                        @for($i = $paging['totalPage'] - 5; $i <= $paging['totalPage']; $i++) <li
                                            @if($i==$paging['pageNumber']) class="active page-item" @else
                                            class="page-item" @endif><a class="page-link " href="#"
                                                onclick="paging({{ $i }})">{{ $i }}</a></li>
                                            @endfor
                                            @else
                                            <li class="disabled page-item"><a class="page-link " href="#">...</a></li>
                                            <li><a class="page-link " href="#"
                                                    onclick="paging({{ $paging['pageNumber'] - 1 }})">{{ $paging['pageNumber'] - 1 }}</a>
                                            </li>
                                            <li class="active page-item"><a class="page-link "
                                                    href="#">{{ $paging['pageNumber'] }}</a></li>
                                            @if ($paging['pageNumber'] + 1 <= $paging['totalPage']) <li><a
                                                    class="page-link " href="#"
                                                    onclick="paging({{ $paging['pageNumber'] + 1 }})">{{ $paging['pageNumber'] + 1 }}</a>
                                                </li>
                                                <li class="disabled page-item"><a class="page-link " href="#">...</a>
                                                </li>
                                                <li class="page-item"><a class="page-link " href="#"
                                                        onclick="paging({{ $paging['totalPage'] }})">{{ $paging['totalPage'] }}</a>
                                                </li>
                                                @endif
                                                @endif
                                                @if ($paging['pageNumber'] < $paging['totalPage']) <li
                                                    class="page-item"><a class="page-link " href="#"
                                                        onclick="paging({{ $paging['pageNumber'] + 1 }})">Next</a>
                                                    </li>
                                                    @endif
                                                    @endif
                                                    @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<!--dateRangePicker-->
<script src="{{ asset('/admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/timepicker/js/bootstrap-timepicker.js') }}"></script>
{{-- lightbox --}}
<script src="{{asset('/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('/admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{ asset('/admin/plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>




@endpush
@push('scripts')
<script>
    $(function () {
        
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true
        });
        });


        $('#_rowPerPage').on('change', function (e) {
                var valueSelected = this.value;
                $('#rowPerPage').val(valueSelected);
                $('#frm').submit();
            });

        // Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
        // Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
        // Money Euro
        $('[data-mask]').inputmask()
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
            format: 'MM/DD/YYYY hh:mm A'
        }
        })
    
        $('#daterange_arrived-btn').daterangepicker(
            {
                ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate  : moment(),
                opens    : 'right'
            },
            function (start, end) {
                // $('#daterange-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))
                $('#daterange_arrived-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))
                $('#daterangestart_arrived').val(start.format('YYYY-MM-DD'))
                $('#daterangeend_arrived').val(end.format('YYYY-MM-DD'))
                // $('#datatable').DataTable().draw(true);
            }
        )
        $('#datepicker').datepicker({
            autoclose: true
        })
        $('#daterange_handover-btn').daterangepicker(
            {
                ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate  : moment(),
                opens    : 'right'
            },
            function (start, end) {
                // $('#daterange-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))
                $('#daterange_handover-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))
                $('#daterangestart_handover').val(start.format('YYYY-MM-DD'))
                $('#daterangeend_handover').val(end.format('YYYY-MM-DD'))
                // $('#datatable').DataTable().draw(true);
            }
        )
        $('#datepicker').datepicker({
            autoclose: true
        })
        
    });
    
    function paging(pageNumber) {
            $('#pageNumber').val(pageNumber);
            $('#frm').submit();
        }
    $('#daterange_arrived-btn').click(()=>{
        var daterangestart = $('#daterangestart_arrived').val();
        var daterangeend = $('#daterangeend_arrived').val();
    });
    $('#daterange_handover-btn').click(()=>{
        var daterangestart = $('#daterangestart_handover').val();
        var daterangeend = $('#daterangeend_handover').val();
    });



    
</script>
@endpush