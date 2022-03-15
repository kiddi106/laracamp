@extends('layouts.app', ['title' => 'Rework'])

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="nav-icon fas fa-cog"></i> Rework</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-cog"></i> Rework</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@endsection

@section('content')
<form action="{{ route('rework.store') }}" method="post" id="frm">
    @csrf
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="_router_id">Router</label>
                                <select class="form-control" id="_router_id" name="router_id"></select>
                                <!-- /input-group -->
                            </div>

                            <div id="router-info" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <dl>
                                            <dt>ESN</dt>
                                            <dd id="_router_esn"></dd>
                                            <dt>SSID</dt>
                                            <dd id="_router_ssid"></dd>
                                            <dt>Password Router</dt>
                                            <dd id="_router_password_router"></dd>
                                            <dt>Guest SSID</dt>
                                            <dd id="_router_guest_ssid"></dd>
                                            <dt>Password Admin Web</dt>
                                            <dd id="_router_password_admin"></dd>
                                        </dl>
                                    </div>
                                    <div class="col-sm-6">
                                        <dl>
                                            <dt>IMEI</dt>
                                            <dd id="_router_imei"></dd>
                                            <dt>Device Model</dt>
                                            <dd id="_router_device_model"></dd>
                                            <dt>Device Type</dt>
                                            <dd id="_router_device_type"></dd>
                                            <dt>Color</dt>
                                            <dd id="_router_color"></dd>
                                            <dt>Condition</dt>
                                            <dd id="_router_condition"></dd>
                                        </dl>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="_simcard_id">Simcard</label>
                                <select class="form-control" id="_simcard_id" name="simcard_id"></select>
                            </div>

                            <div id="simcard-info" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <dl>
                                            <dt>SN</dt>
                                            <dd id="_simcard_serial_no"></dd>
                                            <dt>MSISDN</dt>
                                            <dd id="_simcard_msisdn"></dd>
                                            <dt>Item Code</dt>
                                            <dd id="_simcard_item_code"></dd>
                                            <dt>Expired</dt>
                                            <dd id="_simcard_exp_at"></dd>
                                            <dt>Condition</dt>
                                            <dd id="_simcard_condition"></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="_purchase_type_id">Purchase Type</label>
                                <select name="purchase_type_id" id="_purchase_type_id" class="form-control" name="purchase_type_id">
                                    @foreach (App\Models\PurchaseType::all() as $purchaseType)
                                        <option value="{{ $purchaseType->id }}">{{ $purchaseType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="_location">Location</label>
                                <input type="text" name="location" id="_location" class="form-control" placeholder="location" name="location">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="float-right">
                        <button type="button" class="btn btn-default" id="btn-uploadform" data-toggle="modal" data-target="#modal_batch"><i class="far fa-file-excel"></i> Batch</button>
                        <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="no-sort" style="width: 30px">No</th>
                            <th>ESN</th>
                            <th>MSISDN</th>
                            <th>SSID</th>
                            <th>IMEI</th>
                            <th>Device Model</th>
                            <th>Purchase Type</th>
                            <th>Location</th>
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
                <h4 class="modal-title" id="modal-title">Batch Rework</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('rework.upload') }}" method="post" id="frm-upload" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="fileExcel">File Rework</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="fileExcel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                <label class="custom-file-label" for="fileExcel">Choose file</label>
                            </div>
                        </div>
                        <a href="{{ asset('data_excel/router/format_upload_router.xlsx') }}" class="btn btn-link">Download Format</a>
                    </div>
                </form>
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
        let routers = [];
        let simcards = [];
        $(() => {
            bsCustomFileInput.init();
            $('#_router_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Search by imei...',
                ajax: {
                    url: "{{ route('router.load_data') }}",
                    method: "POST",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            imei: params.term,
                            status_id: 1,
                            condition: 'GOOD',
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    processResults: function (data) {
                        routers = data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.imei + '-' + item.ssid,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: false
                }
            });

            $("#_router_id").on('select2:select', function (e) {
                $("#router-info").hide();
                if (Array.isArray(routers) && routers.length === 1) {
                    const cols = ['esn','ssid','password_router','guest_ssid','password_admin','imei','device_model','device_type','color','condition'];
                    const router = routers[0];
                    cols.forEach((col, idx) => {
                        if (router[col]) {
                            $("#_router_" + col).html(router[col]);
                        } else {
                            $("#_router_" + col).html('-');
                        }
                    });
                    $("#router-info").show();
                }
            });

            $('#_simcard_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Search by msisdn...',
                ajax: {
                    url: "{{ route('simcard.load_data') }}",
                    method: "POST",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            msisdn: params.term,
                            status_id: 1,
                            condition: 'GOOD',
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    processResults: function (data) {
                        simcards = data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.msisdn,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: false
                }
            });

            $("#_simcard_id").on('select2:select', function (e) {
                $("#simcard-info").hide();
                if (Array.isArray(simcards) && simcards.length === 1) {
                    const cols = ['serial_no', 'msisdn', 'item_code', 'exp_at', 'condition'];
                    const simcard = simcards[0];
                    cols.forEach((col, idx) => {
                        if (simcard[col]) {
                            $("#_simcard_" + col).html(simcard[col]);
                        } else {
                            $("#_simcard_" + col).html('-');
                        }
                    });
                    $("#simcard-info").show();
                }
            });

            $("#_purchase_type_id").select2({
                theme: 'bootstrap4',
                placeholder: 'Select Purchase Type'
            });

            $("#btn-submit").click(() => {
                let frm = $("#frm");
                $.ajax({
                    url: "{{ route('rework.store') }}",
                    method: 'POST',
                    data: frm.serialize(),
                    success: function (response) {
                        frm.trigger('reset');
                        const fields = {
                            router_id: '',
                            simcard_id: ''
                        };
                        $.each(fields, function (key, value) {
                                $('#_' + key)
                                    .closest('.form-control')
                                    .removeClass('is-invalid')
                            });
                        $("#router-info").hide();
                        $("#simcard-info").hide();
                        $("#_router_id").empty();
                        $("#_simcard_id").empty();
                        $("#_location").val('');
                        routers = [];
                        simcards = [];
                        $('#datatable').DataTable().ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Rework Success!',
                            timer: 2000,
                            confirmButtonColor: '#3085d6',
                        });
                    },
                    error: function (xhr) {
                        var res = xhr.responseJSON.errors;
                        if ($.isEmptyObject(res) == false) {
                            $.each(res, function (key, value) {
                                $('#_' + key)
                                    .closest('.form-control')
                                    .addClass('is-invalid')
                            });
                        }
                    }
                });
            });


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
                    url: "{{ route('rework.datatable') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function ( d ) {
                        return $.extend( {}, d, {
                        });
                    }
                },
                dom: '<"toolbar">lfrtip',
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'esn', name: 'esn'},
                    {data: 'msisdn', name: 'msisdn'},
                    {data: 'ssid', name: 'ssid'},
                    {data: 'imei', name: 'imei'},
                    {data: 'material_name', name: 'material_name'},
                    {data: 'purchase_type_name', name: 'purchase_type_name'},
                    {data: 'location', name: 'location'}
                ],
                columnDefs: [ {
                    "targets"  : 'no-sort',
                    "orderable": false,
                }],
                order: [[ 4, "asc" ]]
            });

            $('#uploadExcel').on('click', function() {
                var file_data = $('#fileExcel').prop('files')[0];
                if (typeof file_data !== undefined) {
                    Swal.showLoading();
                    $('#uploadExcel').attr('disabled', '');
                    $("#frm-upload").submit();
                }
            });
        });
    </script>
@endpush
