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

</style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Project Payroll</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Payroll</li>
                <li class="breadcrumb-item active">Project</li>
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
                  <form action="{{ route("payroll.project.downloadPaylist") }}" method="POST">
                    @csrf
                    <div class="card-header">
                      <button class="btn btn-secondary" type="submit">Download Payroll List</button>
                      <div class="card-tools">
                        <a href="{{ route('payroll.project.create') }}" class="btn btn-success">Add Payroll</a>
                      </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm" id="datatable">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Project</th>
                                  <th>Month</th>
                                  <th>Year</th>
                                  <th>Status</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
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
      $(function () {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            // searching: false,
            orderable:true,
            destroy: true,
            paging:false,
            info:false,
            ajax: {
                url: '{{ route('payroll.project.datatables') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // data: function ( d ) {
                //     return $.extend( {}, d, {
                //     status_id:status_id
                //     })
                // }
            },
            columns: [
                { data: 'checkbox', name: 'checkbox' },
                { data: 'project', name: 'project' },
                { data: 'month', name: 'month' },
                { data: 'year', name: 'year' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' },
            ],
            order: [[0, "desc"]]
        });
      });
    });
  $('#download').click(function() {
    var projectPayrolId = $("input[name='projectPayrolId[]']:checkbox:checked").map(function(){return $(this).val();}).get()
    $.ajax({
        url: '{{ route("payroll.project.downloadPaylist") }}',
        type: "POST",
        data: {
            '_method': 'POST',
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'projectPayrolId': projectPayrolId,
        },
        success: function (response) {

            if (response.res == 'success') {
                Swal.fire({
                    type: 'success',
                    title: 'Success!',
                    text: 'Data has been Downloaded !'
                });            
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            }
        },
        error: function (xhr) {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
            });
        }
    })
  })
</script>    
@endpush

@endsection
