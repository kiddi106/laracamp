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
              <h1 class="m-0 text-dark">Project Report</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Project</li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item">Report Attendances</li>
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
                        <h3>Report Attendances</h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="date" id="date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" placeholder="dd/mm/yyyy" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                            </div>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Company</label>
                            <select class="form-control select2bs4" style="width: 100%;" name="company" id="company" required>
                              <option value="">Select Company</option>
                              @foreach ($companies as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Project</label>
                            <select class="form-control select2bs4" style="width: 100%;" name="department" id="department">
                              <option value="all">--All Project--</option>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-1">
                          <div class="form-group">
                              <label>&nbsp;</label>
                              <button type="button" class="btn btn-primary form-control" id="export"><i class="fa fa-next-arrow"></i> Export</button>
                          </div>
                        </div>
                        <!-- /.col -->
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
    });

    $('#company').change( () => {
      Swal.showLoading();
      var company = $('#company').val();
      $('#department').empty();
      $.ajax({
          url : '{{ route("er.project.department") }}',
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          dataType : 'json',
          data: {
              company
          },
          success: (data) => {
              var str_data = '<option value="all">--All Project--</option>';
              str_data += data.map((department) => {
                  return '<option value="'+department.code+'">'+department.name+'</option>';
              });
              $('#department').append(str_data);
              Swal.close();
          },
          error: (xhr) => {
              console.log(xhr);
              Swal.close();
          }
      });
    });
  });
    
  $('#export').click(function() {
      var date = $("#date").val()
      var department = $("#department").val()
      var company = $("#company").val()
      if (date != '' && department != '') {
        window.location.href = "/er/project/report/export-attendances/"+company+"/"+department+"/"+encodeURI(date);
      } else {
        Swal.fire({
          type: 'error',
          title : 'Please select Date and Project',
          confirmButtonText: 'OK',
        })
      }
  })
</script>    
@endpush