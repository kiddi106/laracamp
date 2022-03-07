@extends('layouts.app')

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
                            {{-- @permission('create-shift') --}}
                            <div class="pull-right" align="right">
                                <a href="{{ route('mst.shift.create') }}" class="btn btn-sm btn-success modal-show" title="Add Shift"><i class="fa fa-plus"></i> Add Shift</a>
                            </div>
                            {{-- @endpermission --}}
                    </div>
                    <div class="card-body">
                        

                        <table id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Shift Code</th>
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

@push('scripts')
<script>
    $(document).ready( function () {
      $(function () {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('mst.shift.dataTables') !!}',
            columns: [
                { data: 'DT_RowIndex', name: 'shift_cd' },
                { data: 'shift_cd', name: 'shift_cd' },
                { data: 'shift_nm', name: 'shift_nm' },
                { data: 'in', name: 'in' },
                { data: 'out', me: 'sched_out' },
            ]
        });
      });
    });

</script>    
@endpush