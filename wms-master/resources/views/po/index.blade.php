@extends('layouts.app', ['title' => 'Receive Purchase Order - List'])

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="nav-icon fas fa-table"></i> Receive Purchase Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-inbox"></i> Receive Purchase Order</li>
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-table"></i> List</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="no-sort" style="width: 30px">No</th>
                            <th>No. PO</th>
                            <th>PO Date</th>
                            <th>Delivery No</th>
                            <th>Delivery Date</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                            <th class="no-sort" style="width: 30px"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#datatable').DataTable({
            paging: true,
            searching: false,
            ordering: true,
            autoWidth: false,
            responsive: true,
            processing: true,
            serverSide: true,
            cache: false,
            ajax: {
                url: "{{ route('po.datatable') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function ( d ) {
                    return $.extend( {}, d, {
                        name: $("#_name").val(),
                        email: $("#_email").val(),
                        role_id: $("#_role_ids").val()
                    });
                }
            },
            dom: '<"toolbar">lfrtip',
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'po_no', name: 'po_no'},
                {data: 'po_at', name: 'po_at'},
                {data: 'delivery_no', name: 'delivery_no'},
                {data: 'receive_at', name: 'receive_at'},
                {data: 'created_at', name: 'created_at'},
                {data: 'created_name', name: 'created_name'},
                {data: 'action', name: 'action'}
            ],
            columnDefs: [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }],
            order: [[ 5, "asc" ]]
        });

        const toolbar = '<div class="dt-buttons btn-group flex-wrap">'
            + '<a href="{{ route("po.create") }}" class="btn btn-sm btn-success" title="Create New User"><i class="fas fa-plus"></i> Create</a>'
            + '</div>';
        $("div.toolbar").html(toolbar);

        $("#_search").click(() => {
            $('#datatable').DataTable().ajax.reload();
        });
    });
</script>
@endpush
