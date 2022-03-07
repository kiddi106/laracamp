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
                <h1 class="m-0 text-dark">Employee Attendance</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Attendance</a></li>
                    <li class="breadcrumb-item active">Employee</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form class="form-inline">
                <div class="col-md-6">
                    <h3>{{ date('F Y') }}</h3>
                </div>
                <div class="col-md-6">
                    {{-- <button type="button" id="export" class="btn btn-success float-right"><i class="fa fa-file-excel"></i> Export</a> --}}
                </div>
            </form>
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
                <button type="button" id="search" class="btn btn-primary mb-2 mx-sm-1">Search</button>
                <button type="button" id="export" class="btn btn-success float-right mb-2"><i class="fa fa-file-excel"></i> Export</button>
            </form>
            
            <div class="col-md-12">
                <table class="table table-bordered table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee Name</th>
                            <th>Departmen</th>
                            <th>Role</th>
                            <th>Time In</th>
                            <th>Location In</th>
                            <th>Time Out</th>
                            <th>Location Out</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-location" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header" id="modal-header">
                    <h4 class="modal-title" id="modal-title">Location</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
    
                <div class="modal-body" id="modal-body-loc">
                </div>
    
                <div class="modal-footer" id="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>    
@endsection

@push('scripts')
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>

<script>
    $(document).ready(function(){
        datatables()
    })
    $('#date').daterangepicker({
        locale: {
            separator: ' to ',
            format: 'DD-MM-YYYY'
        }
    })
    $('#search').click(function(){
        datatables()
    })

function datatables() {
    $(function () {
        $('#datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('attendance.table_emp') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function ( d ) {
                    return $.extend( {}, d, {
                        date: $("#date").val()
                    })
                }
            },
            columns: [
                { data: 'tanggal', name: 'date' },
                { data: 'emp_name', name: 'emp_name' },
                { data: 'department_name', name: 'department_name' },
                { data: 'name', name: 'name' },
                { data: 'in', name: 'time_in' },
                { data: 'loc_in', name: 'loc_in' },
                { data: 'out', name: 'time_out' },
                { data: 'loc_out', name: 'loc_out' },
                { data: 'task', name: 'task' },
            ]
        });
    })
}
$('body').on('click', '.show-loc', function (event) {
            event.preventDefault();

            var me = $(this),
                url = me.attr('href'),
                title = me.attr('title');

            $('#modal-title').text(title);
            $('#modal-btn-save').addClass('hide');

            $.ajax({
                url: url,
                dataType: 'html',
                success: function (response) {
                    $('#modal-body-loc').html(response);
                }
            });

            $('#modal-location').modal('show');
        });
        $('#export').click(function() {
        var date = $("#date").val()
            window.location.href = "/attendance/exportEmp/"+date;
    })
</script>
@endpush