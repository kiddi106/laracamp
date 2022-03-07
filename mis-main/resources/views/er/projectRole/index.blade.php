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
                        <h5 class="card-title">Roles</h5>
                    </div>
                    <div class="card-body">
                        

                        <table id="datatable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Project</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
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

@push('js')
    <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('scripts')

<script>
            //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $(function () {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('er.project.roles.dataTables') !!}',
            columns: [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'project.name', name: 'project.name' },
                { data: 'display_name', name: 'display_name' },
                { data: 'description', name: 'description' },
                { data: 'action', name: 'action' },
            ]
        });
      });

</script>    
@endpush

@endsection
