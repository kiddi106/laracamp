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
                <li class="breadcrumb-item">Menu</li>
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
                        <h2 class="card-title">Menu</h2>
                            <div class="pull-right" align="right">
                                <a href="{{ route('ga.asset.create') }}" class="btn btn-sm btn-success modal-show" title="Add Asset"><i class="fa fa-plus"></i> Add Asset</a>
                            </div>
                    </div>
                    <div class="card-body">
                        

                        <table id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Serial Number</th>
                                    <th>Asset Number</th>
                                    <th>Specification</th>
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
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: '{!! route('ga.asset.dataTables') !!}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function ( d ) {
                return $.extend( {}, d, {})
            }
        },
        columns: [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'brand', name: 'brand' },
                { data: 'category', name: 'category' },
                { data: 'type', name: 'type' },
                { data: 'serial_no', name: 'serial_no' },
                { data: 'asset_no', name: 'asset_no' },
                { data: 'specification', name: 'specification' }
            ]
    });

</script>    
@endpush

@endsection
