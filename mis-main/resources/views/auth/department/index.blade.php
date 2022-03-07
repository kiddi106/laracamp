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
                        <h5 class="card-title">Department</h5>
                            @permission('create-deaprtement')
                            <div class="pull-right" align="right">
                                <a href="{{ route('config.department.create') }}" class="btn btn-sm btn-success modal-show" title="Create Department"><i class="fa fa-plus"></i> Create Department</a>
                            </div>
                            @endpermission
                    </div>
                    <div class="card-body">
                        

                        <table id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Department Code</th>
                                    <th>Department Name</th>
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
            ajax: '{!! route('config.department.dataTables') !!}',
            columns: [
                { data: 'DT_RowIndex', name: 'code' },
                { data: 'code', name: 'code' },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action' },
            ]
        });
      });
    });

</script>    
@endpush