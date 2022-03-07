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
              <h1 class="m-0 text-dark"> JO Employeement - Confirmatiton</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">ER</li>
                <li class="breadcrumb-item">JO Employeement</li>
                <li class="breadcrumb-item">Confirmatiton</li>
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
                  <form method="POST" action="{{ route('er.jo.employeement.store') }}">
                    @csrf
                    <div class="card-header">
                      <div class="row">
                        <div class="col-12">
                          <div class="col-1 float-left">
                            <label>Project Name:</label>
                          </div>
                          <div class="col-3 float-left">
                           {{ $empls[0]->client_nm . " - " .$empls[0]->job_nm }}
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="col-1 float-left">
                            <label>Assign To:</label>
                          </div>
                          <div class="col-3 float-left">
                            <select class="form-control-sm select2bs4" style="width: 100%;" name="er_empl" id="er_empl">
                              <option value="null" selected>Select ER</option>
                              @foreach ($er as $item)
                                <option value="{{ $item->uuid }}">{{ $item->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-2 float-left">
                            <button class="btn btn-sm btn-primary">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-hover table-sm" style="width: 100%">
                            <thead>
                                <tr>
                                  <th>JO ID</th>
                                  <th>MID</th>
                                  <th>Full Name</th>
                                  <th>Email</th>
                                  <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($empls as $item)
                                  <tr>
                                    <td>{{ $item->jo_id }}</td>
                                    <td>
                                      <input type="hidden" name="employees[]" value="{{ $item->cand_id }}">
                                      <input type="hidden" name="jo_id[]" value="{{ $item->jo_id }}">
                                      {{ $item->cand_id }}
                                    </td>
                                    <td>{{ $item->full_nm }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->job_pos_nm }}</td>
                                  </tr>
                              @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </form>
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
      //Initialize Select2 Elements
      $('.select2bs4').select2({
          theme: 'bootstrap4'
      });
    });
</script>    
@endpush

@endsection
