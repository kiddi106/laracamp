@extends('layouts.app')

{{-- @include('datepicker') --}}
@push('css')
  <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
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
        border: 1px solid rgba(0,0,0,.15);
        border-radius: .25rem;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.175);
	}

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
                <h1 class="m-0 text-dark">Permission</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item ">Permission</a></li>
                    <li class="breadcrumb-item active">Regular Permission</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="form-inline">
                <div class="col-md-6">
                    <h3>Regular Permission</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
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
                                <input type="text" class="form-control float-right date" id="date">
                            </div>
                        </div>
                        <button type="button" id="search" class="btn btn-primary mb-2">Search</button>
                    </form>
                </div>
                <div class="col-md-6">
                                <a href="{{ route('permission.regular.create') }}" class="btn btn-sm btn-success modal-show float-right" title="New Form"><i class="fa fa-plus"></i> New Form</a>
                </div>
            </div>
            
            <div class="col-md-12">
                <table class="table table-bordered table-sm" style="width: 100%" id="datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Request Number</th>
                            <th>Name</th>
                            <th>Request Date</th>
                            <th>Request Type</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(document).ready( function () {
        datatables()
    });

    $('#search').click(function(){
        datatables()
    })

    $('.date').daterangepicker({
		locale: {
            format: 'DD/MM/YYYY'
        }
	})

    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    })



function datatables() {
    $(function () {
        $('#datatable').DataTable({
            destroy: true,
            processing: true,
			serverSide: true,
			order: [[1, "desc" ]],
            ajax: {
                url: "{{ route('permission.regular.dataTables') }}",
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
                { data: 'DT_RowIndex', name: 'permission_id' },
                { data: 'permission_id', name: 'permission_id' },
                { data: 'emp_name', name: 'emp_name' },
                { data: 'request_date', name: 'req_date' },
                { data: 'type_permission', name: 'type_permission' },
                { data: 'status', name: 'status_id' },
                { data: 'note', name: 'note' },
                { data: 'action', name: 'action' },
            ]
        });
    })
}

</script>
@endpush