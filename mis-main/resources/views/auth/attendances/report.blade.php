@extends('layouts.app')

{{-- @include('datepicker') --}}
@push('css')
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('styles')
<style>
    .calendar.right {
        display: none !important;
    }
</style>
@endpush
@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Report Attendance</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Attendance</a></li>
                <li class="breadcrumb-item active">Report Attendance</a></li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>
<!-- /.content-header -->
@endsection

@section('content')
<div class="card col-md-5">
    <div class="card-header">
        <div class="form-inline">
            <h3>Export Attendance</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group mx-sm-3 mb-2">
            <label>Date:</label>
        </div>
        <form class="form-inline">
            <div class="form-group mx-sm-3 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control float-right" id="date">
                </div>
            </div>
            <button type="button" id="export" class="btn btn-primary mb-2">Export</button>
            &nbsp;&nbsp;&nbsp;
            <button type="button" id="export2" class="btn btn-outline-primary mb-2">Export 2</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script>
    $('#date').daterangepicker({
        locale: {
            separator: ' to ',
            format: 'DD-MM-YYYY'
        }
    })
    $('#export').click(function() {
        var date = $("#date").val()
            window.location.href = "/employee/attendance/export-new/"+encodeURI(date);
    })
    $('#export2').click(function() {
        var date = $("#date").val()
            window.location.href = "/attendance/export-new2/"+encodeURI(date);
    })
</script>
@endpush
