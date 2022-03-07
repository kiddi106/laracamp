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
              <h1 class="m-0 text-dark"> JO Employeement - Result</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">ER</li>
                <li class="breadcrumb-item">JO Employeement</li>
                <li class="breadcrumb-item">Result</li>
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
                      <div class="row">
                        <div class="col-12">
                          <div class="col-1 float-left">
                            <label>Project Name:</label>
                          </div>
                          <div class="col-3 float-left">
                           {{ $success_empl[0]['project'] }}
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="col-1 float-left">
                            <label>Total Employee:</label>
                          </div>
                          <div class="col-3 float-left">
                           {{ $count_employeement }}
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="col-1 float-left">
                            <label>Success Employee:</label>
                          </div>
                          <div class="col-3 float-left">
                           {{ $count_success }}
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="col-1 float-left">
                            <label>Failed Employee:</label>
                          </div>
                          <div class="col-3 float-left">
                           {{ $count_failed }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-hover table-sm" style="width: 100%">
                            <thead>
                                <tr>
                                  <th style="width: 20px">Status</th>
                                  <th>JO ID</th>
                                  <th>MID</th>
                                  <th>Full Name</th>
                                  <th>Email</th>
                                  <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
                              @if ($success_empl != NULL) 
                              @foreach ($success_empl as $item)
                                <tr>
                                  <td align="center"><i class="fas fa-check-circle text-green" style="font-size: 16px;"></i></td>
                                  <td>{{ $item['jo_id'] }}</td>
                                  <td>
                                    {{ $item['cand_id'] }}
                                  </td>
                                  <td>{{ $item['full_nm'] }}</td>
                                  <td>{{ $item['email'] }}</td>
                                  <td>{{ $item['position'] }}</td>
                                </tr>
                              @endforeach
                              @endif
                              @if ($failed_empl != NULL) 
                              @foreach ($failed_empl as $item)
                                  <tr>
                                    <td align="center"><i class="fas fa-times-circle text-red" style="font-size: 16px;"></i></td>
                                    <td>{{ $item['jo_id'] }}</td>
                                    <td>
                                      {{ $item['cand_id'] }}
                                    </td>
                                    <td>{{ $item['full_nm'] }}</td>
                                    <td>{{ $item['email'] }}</td>
                                    <td>{{ $item['position'] }}</td>
                                  </tr>
                              @endforeach
                              @endif
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

@if (session('alert.success'))
<script>
    $(document).ready(function(){
        Swal.fire({
            type: 'success',
            title: '{{ session('alert.success') }}',
            timer: 2000,
        })
    })
</script>
@endif
@if (session('alert.failed'))
<script>
    $(document).ready(function(){
        Swal.fire({
            type: 'danger',
            title: '{{ session('alert.failed') }}',
            timer: 2000,
        })
    })
</script>
@endif

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
      //Initialize Select2 Elements
      $('.select2bs4').select2({
          theme: 'bootstrap4'
      });
    });
</script>  

@if (!empty($alert_success))
<script>
    $(document).ready(function(){
        Swal.fire({
            type: 'success',
            title: '{{ $alert_success }}',
            timer: 2000,
        })
    })
</script>
@endif
@endpush

@endsection
