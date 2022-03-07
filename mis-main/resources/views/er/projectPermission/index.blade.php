@extends('layouts.app')
@push('css')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Project</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Project</li>
                <li class="breadcrumb-item">Permission</li>
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
                        <h5 class="card-title">Permission</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>Project</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="department" id="department">
                                  <option value="">Select Project</option>
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
                                <label>Position</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="role" id="role">
                                <option value="">--Select Position--</option>
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
@push('js')
    <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush
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
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    });

    $('#department').change( () => {
        Swal.showLoading();
        var department_code = $('#department').val();
        $('#role').empty();
        $.ajax({
            url : '{{ route("er.project.roles") }}',
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
            url : '{{ route("er.project.employees") }}',
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
      emp()
    });

    function emp() {
      var department = $('#department').val()
      var role = $('#role').val()
      var emp = $('#employee').val()
      if (emp != '') {
        Swal.showLoading();
        var date = $('#date').val();
        $.ajax({
            url : '{{ route("er.project.permission.empPermission") }}',
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
                $('#tableSet').html(data)
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