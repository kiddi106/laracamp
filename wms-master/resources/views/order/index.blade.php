@extends('layouts.app', ['title' => 'Orders', 'collapse' => true])

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="nav-icon fas fa-table"></i> Orders</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-table"></i> Orders</li>
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
        <div class="row justify-content-center">
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-warning"><i class="fas fa-edit"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Requested</span>
                    <span class="info-box-number">{{ $requested }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-lightblue"><i class="fas fa-inbox"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Received</span>
                    <span class="info-box-number">{{ $received }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Ready To Pickup</span>
                    <span class="info-box-number">{{ $ready }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-success"><i class="fas fa-shipping-fast"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Picked Up</span>
                    <span class="info-box-number">{{ $picked }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Canceled</span>
                    <span class="info-box-number">{{ $cancel }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter :</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="_order_no">Order Number</label>
                            <input id="_order_no" type="text" class="form-control" placeholder="WMS...">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="_document_number">Document Number</label>
                            <input id="_document_number" type="text" class="form-control" placeholder="Document Number">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="_awb">AWB</label>
                            <input id="_awb" type="text" class="form-control" placeholder="AWB Number">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="_purchase_type_id">Purchase Type</label>
                            <select id="_purchase_type_id" class="form-control select2bs4" data-placeholder="All Purchase Type" style="width: 100%;">
                                <option value="0">---All Purchase Type---</option>
                                @foreach (App\Models\PurchaseType::all() as $purchaseType)
                                <option value="{{ $purchaseType->id }}">{{ $purchaseType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="_status_id">Status</label>
                            <select id="_status_id" class="form-control select2bs4" data-placeholder="All Status" style="width: 100%;">
                                <option value="0">---All Status---</option>
                                @foreach (App\Models\MstStatus::all() as $status)
                                    @if ($status->id != 2)
                                    <option value="{{ $status->id }}" @if($status->id == 1) selected @endif
                                        {{-- @if(isset($_GET['status_id']) && $_GET['status_id'] == $status->id) selected @endif --}}
                                        >{{ $status->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <button type="button" class="btn btn-sm btn-info float-right" id="_search"><i class="fas fa-search"></i>
                    Search</button>
            </div>
            <!-- /.card-footer -->
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-default" id="btnDownload">Download Order</button>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-sm table-bordered table-hover dt-bootstrap4">
                    <thead>
                        <tr>
                            <th class="no-sort" style="width: 30px" data-priority="1">No</th>
                            <th>Order No.</th>
                            <th>Document No.</th>
                            <th>AWB</th>
                            <th>Customer Name</th>
                            <th>Delivery</th>
                            <th>Delivery Date</th>
                            <th>Purchase Type</th>
                            <th>Status</th>
                            <th>SKU</th>
                            <th>Order By</th>
                            <th>Order Date</th>
                            <th class="no-sort" style="width: 30px" data-priority="1"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade"role="dialog" aria-labelledby="modal" aria-hidden="true" id="modal_download">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_download-title">Download Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="date">Date Range</label>
                    <input type="text" class="form-control" id="date">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="download" class="btn btn-success float-right">Download</button>
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
                url: "{{ route('order.datatable') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function ( d ) {
                    return $.extend( {}, d, {
                        order_no: $("#_order_no").val(),
                        document_number: $("#_document_number").val(),
                        awb: $("#_awb").val(),
                        purchase_type_id: $("#_purchase_type_id").val(),
                        status_id: $("#_status_id").val()
                    });
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'order_number', name: 'order_number'},
                {data: 'document_number', name: 'document_number'},
                {data: 'awb', name: 'awb'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'delivery_name', name: 'delivery_name'},
                {data: 'do_date', name: 'do_date'},
                {data: 'purchase_type_name', name: 'purchase_type_name'},
                {data: 'status_name', name: 'status_name'},
                {data: 'sku', name: 'sku'},
                {data: 'created_user_name', name: 'created_user_name'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action'}
            ],
            columnDefs: [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }],
            order: [[ 2, "asc" ]]
        });

        $("#_search").click(() => {
            $('#datatable').DataTable().ajax.reload();
        });
    });
    $("#btnDownload").click(() => {
        $("#modal_download").modal("show");
    });
    
    $('#date').daterangepicker({
        locale: {
            separator: ' to ',
            format: 'DD-MM-YYYY'
        }
    })
    $('#download').click(function() {
        var date = $("#date").val()
            window.location.href = "/order/report/"+encodeURI(date);
    })
</script>
@endpush
