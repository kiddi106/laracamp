@extends('layouts.app')
@push('css')
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
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
                <li class="breadcrumb-item">Department</li>
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
                        <h5 class="card-title">Shift</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>Date</label>
                              <div class="input-group">
                                  <input type="text" class="form-control datepicker" name="date" id="date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" placeholder="dd/mm/yyyy">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                  </div>
                              </div>
                            </div>
                            <!-- /.form-group -->
                          </div>
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>Department</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="department" id="department">
                                  <option value="">Select Department</option>
                                  @foreach ($departments as $department)
                                    <option value="{{ $department->code }}">{{ $department->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>Role</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="role" id="role">
                                  <option value="">--Select Role--</option>
                                </select>
                              </div>
                              <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2">
                                <div class="form-group">
                                  <label>Employee</label>
                                  <select class="form-control select2bs4" style="width: 100%;" name="employee" id="employee">
                                    <option value="">--Select Employee--</option>
                                  </select>
                                </div>
                                <!-- /.form-group -->
                              </div>
                                <div class="col-md-1">
                                  <div class="form-group">
                                      <label>&nbsp;</label>
                                      <button type="button" class="btn btn-default form-control" id="btnNext"><i class="fa fa-next-arrow"></i> Next</button>
                                  </div>
                              </div>
                        </div>
                        <div id="tableSet">
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

@endsection

@push('scripts')
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script>
    $('#date').daterangepicker({
        locale: {
            separator: ' to ',
            format: 'DD-MM-YYYY'
        }
    })
    $(document).ready( function () {
    //   $(function () {
    //     $('#datatable').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: '{!! route('mst.shift.dataTables') !!}',
    //         columns: [
    //             { data: 'DT_RowIndex', name: 'shift_cd' },
    //             { data: 'shift_cd', name: 'shift_cd' },
    //             { data: 'shift_nm', name: 'shift_nm' },
    //             { data: 'in', name: 'in' },
    //             { data: 'out', me: 'sched_out' },
    //         ]
    //     });
    //   });
    });

    $('#department').change( () => {
        Swal.showLoading();
        var department_code = $('#department').val();
        $('#role').empty();
        $.ajax({
            url : '{{ route("roles") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                department_code
            },
            success: (data) => {
                var str_data = '<option value="">--All Role--</option>';
                str_data += data.map((role) => {
                    return '<option value="'+role.id+'">'+role.display_name+'</option>';
                });
                $('#role').append(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });

    $('#role').change( () => {
        Swal.showLoading();
        var role_id = $('#role').val();
        $('#employee').empty();
        $.ajax({
            url : '{{ route("getEmployee") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                role_id
            },
            success: (data) => {
                var str_data = '<option value="">--All Employees--</option>';
                str_data += data.map((empl) => {
                    return '<option value="' + empl.uuid + '">' + empl.name + ' (' + empl.email +')</option>';
                });
                $('#employee').append(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });

    $('#btnNext').click( () => {
      tanggal()
    });

    function tanggal() {
      var department = $('#department').val()
      var role = $('#role').val()
      var emp = $('#employee').val()
      if (emp != '') {
        Swal.showLoading();
        var date = $('#date').val();
        $.ajax({
            url : '{{ route("mst.shift.dateRange") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'html',
            data: {
                date:date,
                emp:emp
            },
            success: (data) => {
                $("#tableSet").html(data)
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });

      } else {
        Swal.fire({
          type: 'error',
          title : 'Please select Department, Role and Employee',
          confirmButtonText: 'OK',
        })
        $("#tableSet").html('')

      }
    }
</script>    
@endpush