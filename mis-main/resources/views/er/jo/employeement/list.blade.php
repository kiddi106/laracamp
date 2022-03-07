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
              <h1 class="m-0 text-dark"> JO Employeement</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">ER</li>
                <li class="breadcrumb-item">JO Employeement</li>
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
                  <form method="POST" action="{{ route('er.jo.employeement.confirmation') }}">
                    @csrf
                    <div class="card-header">
                      <div class="row">
                        <div class="col-10 float-left">
                          <div class="col-0.5 float-left d-flex h-100">
                            <label class="justify-content-center align-self-center" style="margin-bottom: unset">Filter:</label>
                          </div>
                          <div class="col-3 float-left">
                            <select class="form-control-sm select2bs4" style="width: 100%;" name="src_client" id="src_client">
                              <option value="null" selected>Select Client</option>
                              @foreach ($clients as $item)
                                <option value="{{ $item->client_id }}">{{ $item->client_nm }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-3 float-left">
                            <select class="form-control-sm select2bs4" style="width: 100%;" name="src_job_field" id="src_job_field">
                              <option value="null" selected>Select Job Field</option>
                              @foreach ($field_jobs as $item)
                                <option value="{{ $item->field_job_id }}">{{ $item->job_nm }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="float-left">
                            <button type="button" class="btn btn-default" id="btnSearch"><i class="fa fa-search"></i> Search</button>
                          </div>
                        </div>
                        <div class="col-2">
                          <button class="btn btn-sm btn-success float-right" title="Proccess Employeement" id="proc_empl" type="submit"><i class="fa fa-plus"></i> Employeement</button>
                        </div>
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-hover table-sm" style="width: 100%">
                            <thead>
                                <tr>
                                  <th style="width: 15px" align="center"><input type="checkbox" class='checkall' id='checkall'></th>
                                  <th>JO ID</th>
                                  <th>Project</th>
                                  <th>MID</th>
                                  <th>Full Name</th>
                                  <th>Email</th>
                                  <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
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
      })
      
      $('#checkall').click(function(){
        if($(this).is(':checked')){
          $('.check-empl').prop('checked', true);
        }else{
          $('.check-empl').prop('checked', false);
        }
      });
    });

    $('#btnSearch').click(function(){
      $('#datatable').DataTable({
          searching: true,
          destroy: true,
          orderCellsTop: false,
          fixedHeader: true,
          processing: true,
          serverSide: false,
          "lengthMenu": [ [-1], ["All"] ],
          cache: false,
          ajax: {
            url: "{!! route('er.jo.employeement.list') !!}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function (d) {
              return $.extend( {}, d, {
                  client: $("#src_client").val(),
                  job_field: $("#src_job_field").val(),
              })
            }
          },
          columns: [
              {data: 'action', name: 'action', orderable:false, searchable:false},
              {data: 'jo_id', name: 'jo_id'},
              {data: 'project', name: 'client_nm'},
              {data: 'cand_id', name: 'cand_id'},
              {data: 'full_nm', name: 'full_nm'},
              {data: 'email', name: 'email'},
              {data: 'job_pos_nm', name: 'job_pos_nm'},
          ],
          columnDefs: [
              {"targets" : "no-sort"},
              {"sortable": false, "targets": [0]},
          ]
      });
      $('#btnSearch').click(function(){
        $('#datatable').DataTable().draw(true);
      })
    });
</script>    
@endpush

@endsection
