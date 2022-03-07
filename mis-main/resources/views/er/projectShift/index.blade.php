@extends('layouts.app')
@push('css')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Project Shift</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Project</li>
                <li class="breadcrumb-item">Shift</li>
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
                            {{-- @permission('create-shift') --}}
                            <div class="pull-right" align="right">
                                <a href="{{ route('er.project.shift.create') }}" class="btn btn-sm btn-success modal-show" title="Add Shift"><i class="fa fa-plus"></i> Add Shift</a>
                            </div>
                            {{-- @endpermission --}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- /.col -->
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
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-default form-control" id="btn"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>    

                        <table id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Company</th>
                                    <th>Shift Name</th>
                                    <th>Schedule In</th>
                                    <th>Schedule Out</th>
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
@endpush
@push('scripts')
<script>

    $('#btn').click(function(){
        search()
    })

    $(document).ready(function() {
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
        search()
    })

    function search() {
        $(function () {
            $('#datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                // ajax: '{!! route('er.project.shift.dataTable') !!}',
                ajax: {
                    url: "{{ route('er.project.shift.dataTable') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function ( d ) {
                        return $.extend( {}, d, {
                            company: $("#company").val()
                        })
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id' },
                    { data: 'company', name: 'company' },
                    { data: 'shift_nm', name: 'shift_nm' },
                    { data: 'in', name: 'in' },
                    { data: 'out', name: 'sched_out' },
                ]
            });
        });
    }

</script>    
@endpush