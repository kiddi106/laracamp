@extends('layouts.app')
@push('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<!-- date picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
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
</style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Configuration</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Configuration</li>
                <li class="breadcrumb-item">Vehicle</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
      </div>
      <!-- /.content-header -->
  
      <!-- Main content -->
      <div class="content">
        {{-- <div class="container"> --}}
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Vehicle</h5>
                            {{-- @permission('create-users') --}}
                            <div class="pull-right" align="right">
                                <a href="{{ route('mst.vehicle.create') }}" class="btn btn-sm btn-success modal-show" title="Create Vehicle"><i class="fa fa-plus"></i> Create Vehicle</a>
                            </div>
                            {{-- @endpermission --}}
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-hover table-sm" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>License Plate</th>
                                    <th>Vehicle Type</th>
                                    <th>Vehicle Color</th>
                                    <th>Max Passenger</th>
                                    <th>Driver</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
          </div>
          <!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
      </div>
      <!-- /.content -->
@include('layouts._modal')

@push('js')
    <!-- date picker -->
    <script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/timepicker/js/bootstrap-timepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endpush
@push('scripts')
<script>
    $(document).ready( function () {
      $(function () {
        $('#datatable').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            cache: false,
            ajax: '{!! route('mst.vehicle.list') !!}',
            columns: [
                { data: 'DT_RowIndex', name: 'vehicle_id' },
                { data: 'vehicle_license', name: 'vehicle_license' },
                { data: 'vehicle_type', name: 'vehicle_type' },
                { data: 'vehicle_color', name: 'vehicle_color' },
                { data: 'max_passenger', name: 'max_passenger' },
                { data: 'name', name: 'driver' },
                { data: 'action', name: 'action' },
            ],
            columnDefs: [{
                "targets" : "no-sort",
                "orderable" : false,
            }]
        });
      });
    });

    $('body').on('shown.bs.modal', '.modal', function() {
        create_new_vehicle();
        create_new_driver();
    });

  function create_new_vehicle(){
    var vehicle = $('#vehicle').val();
    if(vehicle == 'N'){
      $('#new_vehicle').show();
    }else{
      $('#new_vehicle').hide();
    }
  }

  function create_new_driver(){
    var driver = $('#driver').val();
    if(driver == 'N'){
      $('#new_driver').show();
    }else{
      $('#new_driver').hide();
    }
  }
</script>    
@endpush

@endsection
