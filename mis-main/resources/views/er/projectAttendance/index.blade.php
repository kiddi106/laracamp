@extends('layouts.app')
@push('css')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">

@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Project Attendance</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Project</li>
                <li class="breadcrumb-item">Attendance</li>
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

                    <div class="card-body">
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>Date</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text">
                                      <i class="far fa-calendar-alt"></i>
                                      </span>
                                  </div>
                                  <input type="text" class="form-control float-right" id="date">
                                </div>
                              </div>
                              <!-- /.form-group -->
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>Company</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="company" id="company">
                                  <option value="">Select Company</option>
                                  @foreach ($companies as $comp)
                                    <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <!-- /.form-group -->
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>Project</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="project" id="project">
                                </select>
                              </div>
                              <!-- /.form-group -->
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-default form-control" id="btn"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-1">
                              <div class="form-group">
                                  <label>&nbsp;</label>
                                  <button type="button" class="btn btn-success form-control" id="export"><i class="fa fa-file-excel"></i> Export</button>
                              </div>
                            </div>
                          <!-- /.col -->
                        </div>    

                        <table id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Employee Name</th>
                                  <th>Shift</th>
                                  <th>Sched In</th>
                                  <th>Time In</th>
                                  <th>Location In</th>
                                  <th>Sched Out</th>
                                  <th>Time Out</th>
                                  <th>Location Out</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
    <script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>

@endpush
@push('scripts')
<script>


    $(document).ready(function() {
      $('#date').daterangepicker({
        locale: {
            separator: ' to ',
            format: 'DD-MM-YYYY'
        }
    })

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    })

    $('#btn').click(function(){
      $('#datatable').DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          // ajax: '{!! route('er.project.shift.dataTable') !!}',
          ajax: {
              url: "{{ route('er.project.attendance.dataTable') }}",
              type: 'post',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: function ( d ) {
                  return $.extend( {}, d, {
                      project: $("#project").val(),
                      date: $("#date").val()
                  })
              }
          },
          columns: [
            { data: 'tanggal', name: 'attendances.date' },
                { data: 'name', name: 'employees.name' },
                { data: 'shift', name: 'shift' },
                { data: 'sched_in', name: 'attendances.sched_in' },
                { data: 'in', name: 'attendances.time_in' },
                { data: 'loc_in', name: 'attendances.loc_in' },
                { data: 'sched_out', name: 'attendances.sched_out' },
                { data: 'out', name: 'attendances.time_out' },
                { data: 'loc_out', name: 'attendances.loc_out' },
          ],
          columnDefs: [
            { searchable: false, targets: [2,3,4,5,6,7,8] }
          ]
      });
    })

    $('#company').change( () => {
        Swal.showLoading();
        var company_id = $('#company').val();
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
                $('#project').html(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });

    $('#export').click(function() {
        var project_code = $('#project').val();
        var date = $("#date").val()
            window.location.href = "/er/project/attendance/exportEmp/"+date+"/"+project_code;
    })
</script>    
@endpush