@extends('layouts.app')

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
                <li class="breadcrumb-item">Detail</li>
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
                    <h4>Detail Asset<h4>

                </div>
            </div>
            <div class="card-body">
                <div class="row row-striped">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-4">
                        <div class="row" style="margin-top:10px; margin-bottom:unset;">
                            <label for="date"
                                class="col-md-4 col-form-label text-md-right">{{ __('Create Date') }}</label>

                            <div class="col-md-6">
                                <input type="hidden" name="empl_id" id="empl_id"
                                    value="{{ base64_encode(Auth::user()->uuid) }}">
                                <input type="hidden" name="date" id="date" value="{{ date('d-m-Y') }}">
                                <label class=" col-form-label"
                                    style="font-weight:normal">{{ $model->asset->created_at }}</label>
                                @error('empl_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div><br>
                        <div class="row" style="margin-bottom:unset;">
                            <label for=""
                                class="col-md-4 col-form-label text-md-right">{{ __('Serial Number') }}</label>

                            <div class="col-md-6">
                                <label class=" col-form-label"
                                    style="font-weight:normal">{{ $model->asset->serial_no }}</label>
                            </div>
                        </div><br>
                        <div class="row">
                            <label for="to_ops"
                                class="col-md-4 col-form-label text-md-right">{{ __('Asset No') }}</label>

                            <div class="col-md-6">
                                <label class="col-form-label"
                                    style="font-weight:normal">{{ $model->asset->asset_no }}</label>
                            </div>
                        </div><br>
                        <div class="row">
                            <label for="from_ops"
                                class="col-md-4 col-form-label text-md-right">{{ __('Allocation') }}</label>

                            <div class="col-md-6">
                                <label class="col-form-label"
                                    style="font-weight:normal">{{ $model->asset->allocation ? $model->asset->allocation : '' }}</label>
                            </div>
                        </div><br>
                        <div class="row">
                            <label for="from_ops"
                                class="col-md-4 col-form-label text-md-right">{{ __('Location') }}</label>

                            <div class="col-md-6">
                                <label class="col-form-label"
                                    style="font-weight:normal">{{ $model->asset->location ? $model->asset->location->city_nm : '' }}</label>
                            </div>
                        </div><br>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label for="from_ops"
                                class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <label class="col-form-label"
                                    style="font-weight:normal">{{ $model->asset->category->category_nm }}</label>
                            </div>
                        </div><br>
                        <div class="row">
                            <label for="from_ops"
                                class="col-md-4 col-form-label text-md-right">{{ __('Brand') }}</label>

                            <div class="col-md-6">
                                <label class="col-form-label"
                                    style="font-weight:normal">{{ $model->asset->brand->brand_nm }}</label>
                            </div>
                        </div><br>
                        <div class="row">
                            <label for="subject" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                            <div class="col-md-6">
                                <label class="col-form-label"
                                    style="font-weight:normal">{{ $model->asset->modelType ? $model->asset->modelType->model_type_nm : '' }}</label>
                            </div>
                        </div><br>
                        <div class="row">
                            <label for="from_ops"
                                class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

                            <div class="col-md-6">
                                <label class="col-form-label"
                                    style="font-weight:normal">{{ $model->status->name }}</label>
                            </div>
                        </div><br>
                        <div class="row">
                            <label for="maker" class="col-md-4 col-form-label text-md-right">{{ __('Create') }}</label>

                            <div class="col-md-6">
                                <input type="hidden" name="id" id="id" value="{{ $model->id }}">
                                <label class=" col-form-label"
                                    style="font-weight:normal">{{ $model->asset->create_by->name }}</label>
                            </div>
                        </div><br>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ asset('/img/ga/asset/'.$model->asset->file_image_nm)}}" data-toggle="lightbox"
                            data-title="sample 3 - red" target="_blank">
                            <img src="{{ asset('/img/ga/asset/'.$model->asset->file_image_nm)}}"
                                onerror="this.src='{{ asset('/img/noimage.png')}}'" class="img-fluid mb-2 " alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <ul>
                    @if ($model->parent_id == 6 || $model->status_id == 6)
                    <li><a href="{{ route('ga.asset.checkin',['id' => $model->asset->id]) }}" type="button"
                            class="btn btn-sm btn-success float-right">Check-In</a></li>
                    @else
                    <li><a href="{{ route('ga.asset.checkout',['id' => $model->asset->id]) }}" type="button"
                            class="btn btn-sm btn-success float-right">Check-Out</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}
@endsection