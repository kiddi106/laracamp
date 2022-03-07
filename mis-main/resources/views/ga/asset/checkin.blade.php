@extends('layouts.app')
@push('css')
{{-- dateRangepicker --}}
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

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
            <h1 class="m-0 text-dark"> Asset Check Out</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Asset</li>
                <li class="breadcrumb-item">CheckOut</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    {{-- </div><!-- /.container-fluid --> --}}
</div>
{{-- <div class="container"> --}}
    <div class="row">
        <div class="col">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <div class="card-header">
                        <h4>PT MITRACOMM EKASARANA</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{!! route('ga.asset.checkin.store') !!}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="asset_id" value="{{$model->asset_id}}">
                            <input type="hidden" name="checkout_to" value="{{$model->checkout->checkout_to}}">
                            <input type="hidden" name="checkout_at" value="{{$model->checkout->checkout_at}}">
                            <div class="form-group row">
                                <label for="category" class="col-md-5 col-form-label text-md-left">Category</label>
                                <input id="asset_no" type="text" class="form-control" name="category"
                                    autocomplete="asset_no" value="{{ $model->asset->category->category_nm }}" autofocus
                                    placeholder="Category" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="asset_no" class="col-md-5 col-form-label text-md-left">Asset Number</label>
                                <input id="asset_no" type="text" class="form-control" name="asset_no"
                                    autocomplete="asset_no" value="{{ $model->asset->asset_no }}" autofocus
                                    placeholder="Asset Number" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="serial_no" class="col-md-5 col-form-label text-md-left">Serial
                                    Number</label>
                                <input id="serial_no" type="text" class="form-control" name="serial_no"
                                    autocomplete="serial_no" value="{{ $model->asset->serial_no }}" autofocus
                                    placeholder="Serial Number" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="brand_nm" class="col-md-5 col-form-label text-md-left">Brand</label>
                                <input id="brand_nm" type="text" class="form-control" name="brand_nm"
                                    autocomplete="brand_nm" value="{{ $model->asset->brand->brand_nm }}" autofocus
                                    placeholder="Brand" disabled>
                            </div>

                            <div class="form-group row">
                                <label for="model_type_nm" class="col-md-5 col-form-label text-md-left">Model
                                    Type</label>
                                <input id="model_type_nm" type="text" class="form-control" name="model_type_nm"
                                    autocomplete="model_type_nm" value="{{ $model->asset->modelType->model_type_nm}}" autofocus
                                    placeholder="Model Type" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="specification"
                                    class="col-md-5 col-form-label text-md-left">Specification</label>
                                <input id="specification" type="text" class="form-control" name="specification"
                                    autocomplete="specification" value="{{ $model->asset->specification }}" autofocus
                                    placeholder="Specification" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="Check-Out" class="col-md-5 col-form-label text-md-left">Check-Out To</label>
                                <input id="CheckOut" type="text" class="form-control" name="checkoutto"
                                    autocomplete="specification" value="{{ $model->checkout->checkoutTo->name }}" autofocus
                                    placeholder="Check-Out To" disabled>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="checkout_date" class="col-form-label text-md-left">Check-Out Date
                                            :</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker col-md-3"
                                                name="checkout_date" id="checkout_date" data-inputmask-alias="datetime"
                                                data-inputmask-inputformat="yyyy-mm-dd" data-mask="" im-insert="false"
                                                placeholder="dd-mm-yyyy" value="{{$model->checkout->checkout_at}}" disabled>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="far fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="checkin_date" class="col-form-label text-md-left">Check-In Date
                                            :</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control datepicker col-md-3 @error('checkin_date') is-invalid @enderror"
                                                name="checkin_date" id="checkin_date" data-inputmask-alias="datetime"
                                                data-inputmask-inputformat="yyyy-mm-dd" data-mask="" im-insert="false"
                                                placeholder="dd-mm-yyyy" readonly>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="far fa-calendar-alt"></i></span>
                                            </div>
                                            @error('checkout_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="Status" class="col-md-8 col-form-label text-md-left">Purmose</label>
                                    <select name="status_id" id="status_id"
                                        class="form-control @error('status_id') is-invalid @enderror">
                                        @foreach (App\Services\Asset::getStatus()->where('parent_id','=','5')->get() as $status)
                                        <option value="{{ $status->status_id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('status_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label for="specification"
                                            class="col-md-5 col-form-label text-md-left">Note</label>
                                        <input id="note" type="text" class="form-control" name="note"
                                            autocomplete="Note" value="" autofocus placeholder="Note">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="maker"
                                    class="col-md-1 col-form-label text-md-left">{{ __('Statement Letter') }}</label>
                                <input type="file" name="file" id="file" class="text-md-left">
                                @error('maker')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-sm bg-green float-right">
                                Next
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}
{{-- </div> --}}
@endsection
@push('js')
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('scripts')
<script>
    $(function (){
        $('.datepicker').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
      })
    })
</script>
@endpush