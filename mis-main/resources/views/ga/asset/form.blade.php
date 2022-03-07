@extends('layouts.app')
@push('css')

{{-- dateRangepicker --}}
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<link rel="stylesheet" href="{{asset('/admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('/admin/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
@endpush
@push('styles')
<style>
    .dropdown-menu-date {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 10rem;
        padding: .5rem 0;
        margin: .125rem 0 0;
        /* font-size: 1rem; */
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: .25rem;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .175);
    }
</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    {{-- <div class="container"> --}}
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark"> Asset</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Asset</li>
                <li class="breadcrumb-item">Create</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    {{-- </div><!-- /.container-fluid --> --}}
</div>
<!-- /.content-header -->
{{-- <div class="container"> --}}
<div class="row">
    <div class="col">
        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <div class="card-header">
                    @if ($model->exists)
                    <h4>Edit Asset<h4>
                            @else
                            <h4>New Asset<h4>
                                    @endif
                </div>
                <div class="card-body">
                    <form class="form-horizontal"
                        action="{!! $model->exists ? route('ga.asset.update',['id' => $model->id]) : route('ga.asset.store') !!}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($model->exists)
                        <input type="hidden" name="_method" value="PUT">
                        @endif
                        <div class="row">
                            @if ($model->exists)
                            <div class="col-md-8">
                                @else
                                <div class="col-md-12">
                                    @endif
                                    <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">Category
                                            <span class="required_star">*</span></label>

                                        <div class="col-md-6">
                                            <select name="category_id" id="category_id" class="form-control select2 select2-hidden-accessible 
                                                @error('category_id') is-invalid @enderror" style="width: 100%;"
                                                data-select2-id="3" tabindex="-3">
                                                <option value="null">---Please Select type---</option>
                                                @foreach (App\Models\Mst\CategoryAsset::All() as $category)
                                                <option value="{{ $category->category_id }}" @if($model->exists &&
                                                    $model->category_id
                                                    ==
                                                    $category->category_id) SELECTED
                                                    @endif>{{ $category->category_nm }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="asset_no" class="col-md-4 col-form-label text-md-right">Asset
                                            Number</label>

                                        <div class="col-md-6">
                                            <input id="asset_no" type="text"
                                                class="form-control @error('asset_no') is-invalid @enderror"
                                                name="asset_no" required autocomplete="asset_no"
                                                value="{{ $model->exists ? $model->asset_no : "" }}" autofocus>
                                            @error('asset_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="serial_no" class="col-md-4 col-form-label text-md-right">Serial
                                            Number</label>

                                        <div class="col-md-6">
                                            <input id="serial_no" type="text"
                                                class="form-control @error('serial_no') is-invalid @enderror"
                                                name="serial_no" required autocomplete="serial_no"
                                                value="{{ $model->exists ? $model->serial_no : "" }}" autofocus>
                                            @error('serial_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="brand" class="col-md-4 col-form-label text-md-right">Brand</label>

                                        <div class="col-md-6">
                                            <select name="brand_id" id="brand_id" class="form-control select2 select2-hidden-accessible 
                                                @error('brand_id') is-invalid @enderror" style="width: 100%;"
                                                data-select2-id="2" tabindex="-2">
                                                <option value="">---Please Select type---</option>
                                                @foreach (App\Models\Mst\BrandAsset::All() as $brand)
                                                <option value="{{ $brand->brand_id }}" @if($model->exists &&
                                                    $model->brand_id
                                                    ==
                                                    $brand->brand_id) SELECTED @endif>{{ $brand->brand_nm }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="type" class="col-md-4 col-form-label text-md-right">Model
                                            Type</label>
                                        <div class="col-md-6">
                                            <select name="modeltype_id" id="modeltype_id" class="form-control select2 select2-hidden-accessible
                                            @error('type_id') is-invalid @enderror" style="width: 100%;"
                                                data-select2-id="4" tabindex="-1">
                                                <option value="">---Please Select type---</option>
                                                @if ($model->brand_id != null)
                                                @php
                                                $modeltype = App\Models\Mst\ModelTypeAsset::query()
                                                ->where('brand_id','=',$model->brand_id)->get()
                                                @endphp
                                                @foreach ($modeltype as $type)
                                                <option value="{{ $type->model_type_id }}" @if($model->exists &&
                                                    $model->model_type_id
                                                    ==
                                                    $type->model_type_id) SELECTED @endif>{{ $type->model_type_nm }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('modeltype_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="specification"
                                            class="col-md-4 col-form-label text-md-right">Specification</label>

                                        <div class="col-md-6">
                                            <textarea class="form-control @error('specification') is-invalid @enderror"
                                                name="specification"> {{ $model->exists ? $model->specification : "" }}</textarea>
                                            @error('specification')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="asset_no" class="col-md-4 col-form-label text-md-right">Request
                                            Date</label>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control datepicker @error('req_date') is-invalid @enderror"
                                                    name="req_date" id="req_date" data-inputmask-alias="datetime"
                                                    data-inputmask-inputformat="yyyy/mm/dd" data-mask=""
                                                    im-insert="false"
                                                    value="{{$model->exists ? date('d-m-Y',strtotime($model->request_dt)) : ""}}"
                                                    placeholder="dd-mm-yyyy" readonly>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="far fa-calendar-alt"></i></span>
                                                </div>
                                                @error('req_dates')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="allocation"
                                            class="col-md-4 col-form-label text-md-right">Allocation</label>

                                        <div class="col-md-6">
                                            <input id="allocation" type="text"
                                                class="form-control @error('allocation') is-invalid @enderror"
                                                name="allocation" autocomplete="allocation"
                                                value="{{ $model->exists ? $model->allocation : "" }}" autofocus>
                                            @error('allocation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="location" class="col-md-4  col-form-label text-md-right">Select
                                            Location</label>
                                        <div class="col-md-6">
                                            <select name="location" id="location"
                                                class="form-control select2 select2-hidden-accessible"
                                                style="width: 100%;" data-select2-id="1" tabindex="-1">
                                                <option value="null">--Select Location Asset--</option>
                                                @foreach (App\Models\Mst\LocationAsset::All() as $location)
                                                <Option Value="{{$location->city_id}}" @if($model->exists &&
                                                    $model->city_id
                                                    ==
                                                    $location->city_id) SELECTED
                                                    @endif>{{$location->city_nm}}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('location')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Arrived" class="col-md-4 col-form-label text-md-right">Goods
                                            Arrived</label>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control datepicker @error('req_date') is-invalid @enderror"
                                                    name="arrived_date" id="arrived_date"
                                                    data-inputmask-alias="datetime"
                                                    data-inputmask-inputformat="yyyy/mm/dd" data-mask=""
                                                    im-insert="false"
                                                    value="{{$model->exists ? date('d-m-Y',strtotime($model->goods_arrived_dt)) : ""}}"
                                                    placeholder="dd-mm-yyyy" readonly>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="far fa-calendar-alt"></i></span>
                                                </div>
                                                @error('arrived_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="note" class="col-md-4 col-form-label text-md-right">Note</label>

                                        <div class="col-md-6">
                                            <textarea class="form-control"
                                                name="note"> {{ $model->exists ? $model->note : "" }}</textarea>
                                            @error('note')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="maker"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Upload Image') }}</label>

                                        <div class="col-md-6">
                                            <input type="file" name="file" id="file" accept="image/*">
                                            @error('maker')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @if ($model->exists)
                                <div class="col-md-2">

                                    <a href="{{ asset('/img/ga/asset/'.$model->file_image_nm)}}" data-toggle="lightbox"
                                        data-title="sample 3 - red" target="_blank">
                                        <img src="{{ asset('/img/ga/asset/'.$model->file_image_nm)}}"
                                            onerror="this.src='{{ asset('/img/noimage.png')}}'" class="img-fluid mb-2 "
                                            alt="">
                                    </a>

                                </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-sm bg-green float-right">
                                @if ($model->exists)
                                Update
                                @else
                                Submit
                                @endif
                            </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}
@endsection
@push('js')
<!--dateRangePicker-->
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/timepicker/js/bootstrap-timepicker.js') }}"></script>
<script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('scripts')
<script>
    $(function (){
        $('.select2').select2()
    })
    $(document).ready(function(){
        $('#brand_id').change(()=>{
            Swal.showLoading();
            var brand_id = $('#brand_id').val();
            $('#modeltype_id').empty();
            $.ajax({
            url: '{{ route('ga.asset.getmodeltype') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                datatype:'json',
                data:{
                    brand_id
                },
                success:(data)=>{
                    // var str_data;
                    var str_data ='<option value="0">---Please Select type---</option>';
                    str_data += data.map((data)=>{
                        return '<option value="'+data.id+'">'+data.name+'</option>';
                    });
                    $('#modeltype_id').append(str_data);
                    Swal.close();
                },
                error:(xhr)=>{
                    console.log(xhr);
                }
            })
        })
        $('.datepicker').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
      })
    //   .inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })

    //   $('.datepicker').datepicker().datepicker("setDate", new Date())
      
      //Timepicker
      $('.timepicker').timepicker({
          showInputs: false
      })
    });
</script>
@endpush