@extends('layouts.app')

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">SIM Cards from Purchase Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-inbox"></i> Receive Purchase Order</li>
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-edit"></i> Edit</li>
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-table"></i> Detail SIM Cards</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@endsection

@section('content')
<div class="card-deck" style="margin-bottom: 1rem;">
    @php
        $po = $poDtl->po;
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <b>{{ $po->po_no }}</b>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">No. Purchase Order</dt>
                <dd class="col-sm-8">{{ $po->po_no }}</dd>
                <dt class="col-sm-4">Purchase Date</dt>
                <dd class="col-sm-8">{{ date('d/M/Y', strtotime($po->po_at)) }}</dd>
                <dt class="col-sm-4">Delivery No.</dt>
                <dd class="col-sm-8">{{ $po->delivery_no }}</dd>
                <dt class="col-sm-4">Receive At</dt>
                <dd class="col-sm-8">{{ date('d/M/Y', strtotime($po->receive_at)) }}</dd>
                <dt class="col-sm-4">Currency</dt>
                <dd class="col-sm-8">
                    @php
                        $currencies = ['Rp' => 'Rupiah', '$' => 'Dollar']
                    @endphp
                    @if ($po->currency == 'Rp')
                        Rupiah
                    @else
                        @if (isset($currencies[$po->currency]))
                            {{ $currencies[$po->currency] }}
                        @endif
                        (Rp {{ $po->kurs }})
                    @endif
                </dd>
                <dt class="col-sm-4">Created by</dt>
                <dd class="col-sm-8">{!! $po->created_user->name . ' - <i class="far fa-calendar"></i> ' . date('d/M/Y', strtotime($po->created_at)) !!}</dd>
            </dl>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <b>{{ $poDtl->material->type->name . '-'. $poDtl->material->name }}</b>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Item</dt>
                <dd class="col-sm-8">{{ $poDtl->material->type->name . '-'. $poDtl->material->name }}</dd>
                <dt class="col-sm-4">Qty</dt>
                <dd class="col-sm-8">{{ number_format($poDtl->qty, 0, ',', '.') . ' ' . $poDtl->uom }}</dd>
                <dt class="col-sm-4">Price</dt>
                <dd class="col-sm-8">{{ $po->currency . ' '. number_format($poDtl->price, 0, ',', '.') }}</dd>
                <dt class="col-sm-4">Total Price</dt>
                <dd class="col-sm-8">Rp {{ number_format($poDtl->total, 0, ',', '.') }}</dd>

                @php
                    $detailed = number_format(App\Models\Simcard::where('po_dtl_id', '=', $poDtl->id)->count(), 0, ',', '.');
                    $good = number_format(App\Models\Simcard::where('po_dtl_id', '=', $poDtl->id)->where('condition', '=', 'GOOD')->count(), 0, ',', '.');
                    $bad = number_format(App\Models\Simcard::where('po_dtl_id', '=', $poDtl->id)->where('condition', '=', 'BAD')->count(), 0, ',', '.');
                @endphp

                <dt class="col-sm-4">Detailed</dt>
                <dd class="col-sm-8 text-primary">{{ $detailed . ' ' . $poDtl->uom }}</dd>
                <dt class="col-sm-4">Good</dt>
                <dd class="col-sm-8 text-success">{{ $good . ' ' . $poDtl->uom }}</dd>
                <dt class="col-sm-4">Bad</dt>
                <dd class="col-sm-8 text-danger">{{ $bad . ' ' . $poDtl->uom }}</dd>
            </dl>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="no-sort" style="width: 30px">No</th>
                            <th>SN</th>
                            <th>MSISDN</th>
                            <th>ITEM CODE</th>
                            <th>Expired</th>
                            <th>Condition</th>
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


@section('modal')
<!-- modal-dialog -->
<div class="modal fade"role="dialog" aria-labelledby="modal" aria-hidden="true" id="modal_batch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Batch</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="fileExcel">File input</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fileExcel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            <label class="custom-file-label" for="fileExcel">Choose file</label>
                        </div>
                    </div>
                    <a href="{{ asset('data_excel/simcard/format_upload_simcard.xlsx') }}" class="btn btn-link">Download Format</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="uploadExcel" class="btn btn-primary float-right">Upload</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <!-- bs-custom-file-input -->
    <script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
@endpush

@push('scripts')
<script>
    $(function () {
        bsCustomFileInput.init();
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
                url: "{{ route('po.dtl.simcard.datatable') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function ( d ) {
                    return $.extend( {}, d, {
                        po_dtl_id: {{ $poDtl->id }},
                    });
                }
            },
            dom: '<"toolbar">lfrtip',
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'serial_no', name: 'serial_no'},
                {data: 'msisdn', name: 'msisdn'},
                {data: 'item_code', name: 'item_code'},
                {data: 'exp_at', name: 'expired_at'},
                {data: 'condition', name: 'condition'},
                {data: 'action', name: 'action'}
            ],
            columnDefs: [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }],
            order: [[ 1, "asc" ]]
        });

        const toolbar = '<div class="dt-buttons btn-group flex-wrap">'
            + '<a href="{{ route("po.dtl.simcard.create", ["po_dtl_id" => $poDtl->id]) }}" class="btn btn-sm btn-default modal-show" title="Add New Router"><i class="fas fa-plus"></i> Add</a>'
            + '<button type="button" class="btn btn-sm btn-default" title="Add New Batch" data-toggle="modal" data-target="#modal_batch"><i class="far fa-file-excel"></i> Batch</button>'
            + '</div>';
        $("div.toolbar").html(toolbar);

        $("#_search").click(() => {
            $('#datatable').DataTable().ajax.reload();
        });

        $('#uploadExcel').on('click', function() {
            var file_data = $('#fileExcel').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('po_dtl_id', {{ $poDtl->id }});
            $('#fileExcel').attr('disabled', '');
            $('#uploadExcel').attr('disabled', '');
            Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "{{ route('po.dtl.simcard.upload') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(res){
                    window.location.reload();
                }
            });
        });
    });
</script>
@endpush
