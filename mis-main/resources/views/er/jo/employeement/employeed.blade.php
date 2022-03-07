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
              <h1 class="m-0 text-dark"> Employeed</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">ER</li>
                <li class="breadcrumb-item">JO Employeement</li>
                <li class="breadcrumb-item">Employeed</li>
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
                      <div class="col-10 float-left">
                        <div class="col-0.5 float-left d-flex h-100">
                          <label class="justify-content-center align-self-center" style="margin-bottom: unset">Filter:</label>
                        </div>
                        <div class="col-3 float-left">
                          <select class="form-control-sm select2bs4" style="width: 100%;" name="src_client" id="src_client">
                            <option value="null" selected>Select Company</option>
                            @foreach ($companies as $comp)
                              <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-3 float-left">
                          <select class="form-control-sm select2bs4" style="width: 100%;" name="src_job_field" id="src_job_field">
                            <option value="null" selected>Select Project</option>
                          </select>
                        </div>
                        <div class="float-left">
                          <button type="button" class="btn btn-default" id="btnSearch"><i class="fa fa-search"></i> Search</button>
                        </div>
                      </div>
                    </div>
                    <!-- /.row -->
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-bordered table-hover table-sm" style="width: 100%">
                          <thead>
                              <tr>
                                <th>Mitracomm ID</th>
                                <th>Full Name</th>
                                <th>Employee ID</th>
                                <th>Project</th>
                                <th>Company</th>
                                <th>Bank Account</th>
                                <th>NPWP</th>
                                <th>Join Date</th>
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
      //Initialize Select2 Elements
      $('.select2bs4').select2({
          theme: 'bootstrap4'
      })
    });

    $('#btnSearch').click(function(){
      $('#datatable').DataTable({
          searching: true,
          destroy: true,
          orderCellsTop: false,
          fixedHeader: true,
          processing: true,
          serverSide: true,
          "lengthMenu": [ [10, 50, 100, -1], ["10","50","100","All"] ],
          cache: false,
          ajax: {
            url: "{!! route('er.jo.employeement.employeedList') !!}",
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
              {data: 'cand_id', name: 'cand_id'},
              {data: 'name', name: 'name'},
              {data: 'empl_id', name: 'empl_id'},
              {data: 'project', name: 'project'},
              {data: 'comp_name', name: 'comp_name'},
              {data: 'bank_account', name: 'bank_account'},
              {data: 'npwp', name: 'npwp'},
              {data: 'join_date', name: 'join_date'},
              {data: 'action', name: 'action', orderable:false, searchable:false},
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

    $('#src_client').change( () => {
        Swal.showLoading();
        var company_id = $('#src_client').val();
        $.ajax({
            url : '{{ route("er.project.attendance.getProject") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
              company_id
            },
            success: (data) => {
                var str_data = '<option value="">Select Project</option>';
                str_data += data.map((project) => {
                    return '<option value="' + project.code + '">' + project.name + '</option>';
                });
                $('#src_job_field').html(str_data);
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

@endsection
