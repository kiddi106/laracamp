@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Variables</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Payroll</li>
                <li class="breadcrumb-item active">Variables</li>
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
                        {{-- <h5 class="card-title">Menu</h5> --}}
                            {{-- @permission('create-roles') --}}
                            <div class="pull-right" align="right">
                                <a href="{{ route('payroll.variables.create') }}" class="btn btn-sm btn-success modal-show" title="Add Variable"><i class="fa fa-plus"></i> Add variable</a>
                                <a href="{{ route('payroll.variables.group') }}" class="btn btn-sm btn-primary modal-show" title="Set Group"><i class="fa fa-plus"></i> Set Group</a>
                            </div>
                            {{-- @endpermission --}}
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>Percentage(%)</th>
                                    <th>Counter</th>
                                    <th>Tax Counter</th>
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
            paging:true,
            info:false,
            ajax: {
                url: '{{ route('payroll.variables.datatables') }}',
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
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type' },
                { data: 'model', name: 'model' },
                { data: 'percentage', name: 'percentage' },
                { data: 'counter', name: 'counter' },
                { data: 'tax_counter', name: 'tax_counters' },
                { data: 'action', name: 'action' },
            ],
            // order: [[1, "asc"]]
        });
      });
    });

</script>    
@endpush

@endsection
