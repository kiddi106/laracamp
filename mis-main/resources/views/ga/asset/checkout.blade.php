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
                        <form class="form-horizontal" action="{!! route('ga.asset.checkout.store') !!}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="asset_id" value="{{$model->asset_id}}">
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
                                    autocomplete="model_type_nm" value="{{ $model->asset->modelType->model_type_nm }}" autofocus
                                    placeholder="Model Type" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="specification"
                                    class="col-md-5 col-form-label text-md-left">Specification</label>
                                <input id="specification" type="text" class="form-control" name="specification"
                                    autocomplete="specification" value="{{ $model->asset->specification }}" autofocus
                                    placeholder="Specification" disabled>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="checkout_to" class="col-md-5 col-form-label text-md-left">Check-Out
                                            To</label>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <select
                                                    class="form-control select2 select2-hidden-accessible @error('checkout_to') is-invalid @enderror"
                                                    style="width: 100%;" tabindex="-1" aria-hidden="true"
                                                    id="checkout_to" name="checkout_to">
                                                    <option value="">---Select User---</option>
                                                    @foreach (App\Models\Mst\EmployeeAsset::All() as $user)
                                                    <option value="{{ $user->uuid }}"> {{ $user->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('checkout_to')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{ route('ga.asset.user.create') }}"
                                                    class="btn btn-success btn-xs modal-show" title="Create User Asset"><i
                                                        class="fa fa-plus"></i> NEW</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department"
                                            class="col-md-5 col-form-label text-md-left">Project</label>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <select
                                                    class="form-control select2 select2-hidden-accessible  @error('department_code') is-invalid @enderror"
                                                    style="width: 100%;" data-select2-id="1" tabindex="-1"
                                                    aria-hidden="true" id="department_code" name="department_code">
                                                    <option value="">---Select Project---</option>
                                                    @foreach (App\Models\Mst\DepartmentAsset::All() as $department)
                                                    <option value="{{ $department->code }}"> {{ $department->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('department_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{ route('ga.asset.department.create') }}"
                                                    class="btn btn-success btn-xs modal-show"
                                                    title="Create Project"><i class="fa fa-plus"></i> NEW</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="checkout_date" class="col-form-label text-md-left">Check-Out Date :</label>
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control datepicker col-md-1 @error('checkout_date') is-invalid @enderror"
                                        name="checkout_date" id="checkout_date" data-inputmask-alias="datetime"
                                        data-inputmask-inputformat="yyyy-mm-dd" data-mask="" im-insert="false"
                                        placeholder="dd-mm-yyyy" readonly>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    @error('checkout_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="location"
                                    class="col-md-5 col-form-label">Asset Location</label>

                                    <input class="form-control @error('location') is-invalid @enderror"
                                        name="location">
                                    @error('location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                            <div class="form-group row">
                                <label for="specification" class="col-md-5 col-form-label text-md-left">Note</label>
                                <input id="note" type="text" class="form-control" name="note" autocomplete="Note"
                                    value="" autofocus placeholder="Purpose">
                            </div>
                            <div class="form-group row">
                                <label for="file"
                                    class="col-md-1 col-form-label text-md-left">{{ __('Statement Letter') }}</label>
                                <input type="file" name="file" id="file" class="text-md-left">
                                @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- <div class="card-footer"> --}}
                            <button type="submit" class="btn btn-sm bg-green float-right">
                                Next
                            </button>
                            {{-- </div> --}}
                            {{-- <button type="submit" class="btn btn-primary">Upload File</button> --}}
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
<script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

@endpush

@push('scripts')
<script>
    $(function (){
        $('.select2').select2()
        $('.datepicker').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
      })


    })
    $('#department_code').change( () => {
        Swal.showLoading();
        var department_code = $('#department_code').val();
        $('#checkout_to').empty();
        $.ajax({
            url : '{{ route("ga.asset.getuserdepartment") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                department_code
            },
            success: (data) => {
                var str_data = '<option value="">--Please Select Department--</option>';
                str_data += data.map((empl) => {
                    return '<option value="'+empl.uuid+'">'+empl.name+'</option>';
                });
                $('#checkout_to').append(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });
    $('#checkout_to').change( () => {
        Swal.showLoading();
        var uuid = $('#checkout_to').val();
        $('#department_code').empty();
        $.ajax({
            url : '{{ route("ga.asset.getdepartmentbyuser") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                uuid
            },
            success: (data) => {
                var str_data
                // var str_data = '<option value="">--Please Select User--</option>';
                str_data += data.map((dep) => {
                    return '<option value="'+dep.code+'" SELECTED>'+dep.name+'</option>';
                });
                $('#department_code').append(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });
</script>
@endpush